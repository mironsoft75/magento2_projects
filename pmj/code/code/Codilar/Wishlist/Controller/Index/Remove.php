<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 27/1/19
 * Time: 7:29 PM
 */

namespace Codilar\Wishlist\Controller\Index;

use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Wishlist\Controller\WishlistProviderInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Wishlist\Model\Wishlist as WishlistModel;
/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Remove extends \Magento\Wishlist\Controller\AbstractIndex
{
    /**
     * @var WishlistProviderInterface
     */
    protected $wishlistProvider;

    /**
     * @var Validator
     */
    protected $formKeyValidator;
    /**
     * @var \Magento\Wishlist\Model\Item
     */
    private $wishlist;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var JsonFactory
     */
    private $jsonFactory;
    /**
     * @var CustomerSession
     */
    private $customerSession;
    /**
     * @var WishlistModel
     */
    private $wishlistModel;

    /**
     * @param Action\Context $context
     * @param WishlistProviderInterface $wishlistProvider
     * @param Validator $formKeyValidator
     * @param \Magento\Wishlist\Model\Item $wishlist
     * @param ProductRepository $productRepository
     * @param JsonFactory $jsonFactory
     * @param CustomerSession $customerSession
     * @param WishlistModel $wishlistModel
     */
    public function __construct(
        Action\Context $context,
        WishlistProviderInterface $wishlistProvider,
        Validator $formKeyValidator,
        \Magento\Wishlist\Model\Item $wishlist,
        ProductRepository $productRepository,
        JsonFactory $jsonFactory,
        CustomerSession $customerSession,
        WishlistModel $wishlistModel
    ) {
        $this->wishlistProvider = $wishlistProvider;
        $this->formKeyValidator = $formKeyValidator;
        parent::__construct($context);
        $this->wishlist = $wishlist;
        $this->productRepository = $productRepository;
        $this->jsonFactory = $jsonFactory;
        $this->customerSession = $customerSession;
        $this->wishlistModel = $wishlistModel;
    }


    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $jsonResult = $this->jsonFactory->create();

        $pId = (int)$this->getRequest()->getParam('product');
        if(!$this->customerSession->isLoggedIn()){
            return $jsonResult->setData(['success'=> false, 'message'=> 'Something went wrong while saving', 'redirect' => true]);
        }
        $customerId = $this->customerSession->getCustomerId();
        $wish = $this->wishlistModel->loadByCustomerId($customerId);
        $wishlistItem = $wish->getItemCollection()->addFieldToFilter('product_id',$pId)->getFirstItem();

        $wishlist = $this->wishlistProvider->getWishlist($wishlistItem->getData('wishlist_id'));
        if (!$wishlist) {
            return $jsonResult->setData(['success'=> false, 'message'=> 'Something went wrong while saving', 'redirect' => false]);
        }
        try {
            $wishlistItem->delete();
            $wishlist->save();
            $this->messageManager->addSuccessMessage(__('Successfully removed from Wishlist'));
        } catch (\Exception $e) {
            $this->messageManager->addError(
                __('We can\'t delete the item from Wish List right now because of an error: %1.', $e->getMessage())
            );
        } catch (\Exception $e) {
            $this->messageManager->addError(__('We can\'t delete the item from the Wish List right now.'));
        }

        return $jsonResult->setData(['success'=> 'true',
            'message'=> 'Product Deleted successfully'
        ]);
    }
}
