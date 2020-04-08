<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 27/1/19
 * Time: 7:28 PM
 */

namespace Codilar\Wishlist\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Add extends Action\Action
{
    /**
     * @var \Magento\Wishlist\Controller\WishlistProviderInterface
     */
    protected $wishlistProvider;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Validator
     */
    protected $formKeyValidator;
    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider
     * @param ProductRepositoryInterface $productRepository
     * @param Validator $formKeyValidator
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider,
        ProductRepositoryInterface $productRepository,
        Validator $formKeyValidator,
        JsonFactory $jsonFactory
    ) {

        $this->wishlistProvider = $wishlistProvider;
        $this->productRepository = $productRepository;
        $this->formKeyValidator = $formKeyValidator;
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
        $this->_customerSession = $customerSession;
    }

    /**
     * Adding new item
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @throws NotFoundException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function execute()
    {
        $jsonResult = $this->jsonFactory->create();
        $wishlist = $this->wishlistProvider->getWishlist();
        if (!$wishlist) {
            $this->messageManager->addNoticeMessage(__('Something went wrong while adding!'));
            return $jsonResult->setData(['success'=> false, 'message'=> 'Something went wrong while saving', 'redirect' => false]);
        }

        $session = $this->_customerSession;

        $requestParams = $this->getRequest()->getParams();

        if (!$this->_customerSession->isLoggedIn()) {
            $this->messageManager->addNoticeMessage(__('Please Login to add products to Wishlist'));
            return $jsonResult->setData(['success'=> false, 'message'=> 'Please login', 'redirect' => true]);
        }

        $productId = isset($requestParams['product']) ? (int)$requestParams['product'] : null;
        if (!$productId) {
            return $jsonResult->setData(['success'=> false, 'message'=> 'Product Does not exists', 'redirect' => false]);
        }

        try {
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            $product = null;
        }

        if (!$product || !$product->isVisibleInCatalog()) {
            $this->messageManager->addErrorMessage(__('We can\'t specify a product.'));
            return $jsonResult->setData(['success'=> false, 'message'=> 'Product Does not exists',  'redirect' => false]);

        }

        try {
            $buyRequest = new \Magento\Framework\DataObject($requestParams);

            $result = $wishlist->addNewItem($product, $buyRequest);
            if (is_string($result)) {
                throw new \Magento\Framework\Exception\LocalizedException(__($result));
            }
            if ($wishlist->isObjectNew()) {
                $wishlist->save();
            }
            $this->_eventManager->dispatch(
                'wishlist_add_product',
                ['wishlist' => $wishlist, 'product' => $product, 'item' => $result]
            );

            $referer = $session->getBeforeWishlistUrl();
            if ($referer) {
                $session->setBeforeWishlistUrl(null);
            } else {
                $referer = $this->_redirect->getRefererUrl();
            }

            $this->_objectManager->get(\Magento\Wishlist\Helper\Data::class)->calculate();

            $this->messageManager->addComplexSuccessMessage(
                'addProductSuccessMessage',
                [
                    'product_name' => $product->getName(),
                    'referer' => $referer
                ]
            );
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage(
                __('We can\'t add the item to Wish List right now: %1.', $e->getMessage())
            );
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('We can\'t add the item to Wish List right now.')
            );
        }
        return $jsonResult->setData(['success'=> true,
                'message'=> 'Product added successfully',
                'item' => $result->getId(),
                'redirect' => false
        ]);
    }
}