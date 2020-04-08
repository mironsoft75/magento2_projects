<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Wishlist\Model;

use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\Wishlist\Api\Data\WishlistInterface;
use Codilar\Wishlist\Api\WishlistRepositoryInterface;
use Magento\Catalog\Api\Data\ProductCustomOptionInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductOptions\Config as ProductOptionsConfig;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Rest\Response;
use Magento\Integration\Model\Oauth\Token;
use Magento\Wishlist\Model\ResourceModel\Item as WishlistItemResource;
use Magento\Wishlist\Model\ResourceModel\Wishlist as WishlistResource;
use Magento\Wishlist\Model\ItemFactory;
use Magento\Wishlist\Model\WishlistFactory;
use Codilar\Checkout\Helper\Product as ProductHelper;

class WishlistRepository extends AbstractApi implements WishlistRepositoryInterface
{
    /**
     * @var Wishlist
     */
    private $wishlist;
    /**
     * @var Customer
     */
    private $customer;
    /**
     * @var ItemFactory
     */
    private $wishlistItemFactory;
    /**
     * @var WishlistItemResource
     */
    private $wishlistItemResource;

    /**
     * @var WishlistResource
     */
    private $wishlistResource;
    /**
     * @var WishlistFactory
     */
    private $wishlistFactory;
    /**
     * @var Token
     */
    private $token;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var ProductOptionsConfig
     */
    private $productOptionsConfig;
    /**
     * @var ProductHelper
     */
    private $productHelper;


    /**
     * WishlistRepository constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param Wishlist $wishlist
     * @param Customer $customer
     * @param ItemFactory $wishlistItemFactory
     * @param WishlistItemResource $wishlistItemResource
     * @param WishlistResource $wishlistResource
     * @param WishlistFactory $wishlistFactory
     * @param Token $token
     * @param ProductRepositoryInterface $productRepository
     * @param ProductOptionsConfig $productOptionsConfig
     * @param ProductHelper $productHelper
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        Wishlist $wishlist,
        Customer $customer,
        ItemFactory $wishlistItemFactory,
        WishlistItemResource $wishlistItemResource,
        WishlistResource $wishlistResource,
        WishlistFactory $wishlistFactory,
        Token $token,
        ProductRepositoryInterface $productRepository,
        ProductOptionsConfig $productOptionsConfig,
        ProductHelper $productHelper
    )
    {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->wishlist = $wishlist;
        $this->customerSession = $customerSession;
        $this->customer = $customer;
        $this->wishlistItemFactory = $wishlistItemFactory;
        $this->wishlistItemResource = $wishlistItemResource;
        $this->wishlistResource = $wishlistResource;
        $this->wishlistFactory = $wishlistFactory;
        $this->token = $token;
        $this->productRepository = $productRepository;
        $this->productOptionsConfig = $productOptionsConfig;
        $this->productHelper = $productHelper;
    }

    /**
     * @return \Codilar\Wishlist\Api\Data\WishlistInterface
     */
    public function getWishlist()
    {
        try {
            if ($customerId = $this->getCustomerId()) {
                /** @var WishlistInterface $wishlist */
                $wishlist = $this->wishlist->loadByCustomerId($customerId, true);
                $response = [
                    "status" => true,
                    "message" => __("Customer has wishlist"),
                    "id" => $wishlist->getId(),
                    "customer_id" => $wishlist->getCustomerId(),
                    "has_items" => $wishlist->hasItems(),
                    "shared" => $wishlist->getShared(),
                    "sharing_code" => $wishlist->getSharingCode(),
                    "items_count" => $wishlist->getItemsCount(),
                    "items" => $wishlist->getItems()
                ];
            } else {
                $response = [
                    "status" => false,
                    "message" => __("Customer is not logged in!")
                ];
            }
        } catch (\Exception $e) {
            $response = [
                "status" => false,
                "message" => __("Customer is not logged in!")
            ];
        }
        return $this->sendResponse($this->getNewDataObject($response));
    }


    /**
     * @param int $productId
     * @return \Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterface|mixed
     */
    public function addWishlistItem($productId)
    {
        $response = [
            "status" => true,
            "message" => __("Item has been added in your wishlist!")
        ];
        try {
            if ($customerId = $this->getCustomerId()) {
                if ($customerId) {
                    $wishlist = $this->wishlist->loadByCustomerId($customerId, true);
                    $product = $this->productRepository->getById($productId);
                    $options = [];
                    foreach ($product->getOptions() as $option) {
                        if ($this->productHelper->getProductOptionType($option) == 'select') {
                            $options[$option->getOptionId()] = [];
                        } else {
                            $options[$option->getOptionId()] = '';
                        }
                    }
                    $item = $wishlist->addNewItem($productId, new \Magento\Framework\DataObject([
                        'options' => $options
                    ]));
                    if (is_string($item)) {
                        $response = [
                            'status'  => false,
                            'message' => $item
                        ];
                    } else {
                        $productName = $item->getProduct()->getName();
                        $response['message'] = __("Item %1 has been added to your wishlist!", $productName);
                    }
                } else {
                    $response['status'] = false;
                    $response['message'] = __("Item could not be added.");
                }

            }
        } catch (\Exception $e) {
            $response = [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }

        $response = $this->getNewDataObject($response);
        return $this->sendResponse($response);
    }

    /**
     * @param int $itemId
     * @return \Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterface|mixed
     * @throws LocalizedException
     */
    public function removeWishlistItem($itemId)
    {
        $response = [
            "status" => true,
            "message" => __("Item has been removed from your wishlist!")
        ];
        if ($customerId = $this->getCustomerId()) {
            $item = $this->getWishlistItem($itemId);
            if (!$item) {
                $response = [
                    "status" => false,
                    "message" => __("Could not find the item in your wishlist!")
                ];
            } else {
                $wishlist = $this->getWishlistByCustomerId($customerId, $item->getWishlistId());
                try {
                    $this->wishlistItemResource->delete($item);
                    $this->wishlistResource->save($wishlist);
                } catch (\Exception $e) {
                    $response = [
                        "status" => false,
                        "message" => __("Could not remove %1 from your wishlist!", $item->getProduct()->getName())
                    ];
                }

            }
        } else {
            $response = [
                "status" => false,
                "message" => __("Item does not belong to your wishlist or wishlist does not exists!")
            ];
        }

        $response = $this->getNewDataObject($response);
        return $this->sendResponse($response);
    }

    /**
     * @return bool|int
     */
    protected function getCustomerId()
    {
       return $this->getCustomerIdByToken()?:false;
    }

    /**
     * @return int|bool
     */
    protected function getCustomerIdByToken()
    {
        $token = $this->request->getHeader("Authorization");
        $token = explode(" ", $token);
        if (isset($token[count($token)-1])) {
            $token = $token[count($token)-1];
        } else {
            $token = "";
        }
        if ($token) {
            $tokenModel = $this->token->loadByToken($token);
            return $tokenModel->getCustomerId();
        }
        return false;
    }

    /**
     * @param int $itemId
     * @return \Magento\Wishlist\Model\Item
     */
    protected function getWishlistItem($itemId)
    {
        $item = $this->wishlistItemFactory->create();
        $this->wishlistItemResource->load($item, $itemId);
        return $item;
    }

    /**
     * @param $wishlistId
     * @return \Magento\Wishlist\Model\Wishlist
     */
    protected function _getWishlist($wishlistId)
    {
        $wishlist = $this->wishlistFactory->create();
        $this->wishlistResource->load($wishlist, $wishlistId);
        return $wishlist;
    }

    /**
     * @param $customerId
     * @param $wishlistId
     * @return \Magento\Wishlist\Model\Wishlist|boolean
     */
    protected function getWishlistByCustomerId($customerId, $wishlistId)
    {
        /** @var \Magento\Wishlist\Model\Wishlist $wishlist */
        $wishlist = $this->wishlistFactory->create();
        $this->wishlistResource->load($wishlist, $customerId, "customer_id");
        if ($wishlist->getId() && $wishlistId == $wishlist->getId()) {
            return $wishlist;
        }
        return false;
    }
}