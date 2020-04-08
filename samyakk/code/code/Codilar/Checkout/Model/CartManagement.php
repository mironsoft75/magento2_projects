<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Model;


use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\Api\Helper\Customer as CustomerHelper;
use Codilar\Checkout\Api\CartManagementInterface;
use Codilar\Checkout\Api\Data\CartInterface;
use Codilar\Checkout\Api\Data\CartInterfaceFactory;
use Codilar\Checkout\Api\Data\ItemInterface;
use Codilar\Checkout\Helper\Product as ProductHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Webapi\Rest\Response;
use Magento\Eav\Model\ResourceModel\Entity\Attribute as EavAttribute;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteFactory;
use Codilar\Customer\Api\Data\AbstractResponseInterface;
use Codilar\Customer\Api\Data\AbstractResponseInterfaceFactory;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Store\Model\StoreManager;

class CartManagement extends AbstractApi implements CartManagementInterface
{
    /**
     * @var CartInterfaceFactory
     */
    private $cartFactory;
    /**
     * @var CustomerHelper
     */
    private $customerHelper;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var EavAttribute
     */
    private $eavAttribute;
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;
    /**
     * @var ProductHelper
     */
    private $productHelper;
    /**
     * @var QuoteFactory
     */
    private $quoteFactory;
    /**
     * @var AbstractResponseInterface
     */
    private $abstractResponse;
    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;
    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * CartManagement constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param CartInterfaceFactory $cartFactory
     * @param CustomerHelper $customerHelper
     * @param ProductRepositoryInterface $productRepository
     * @param EavAttribute $eavAttribute
     * @param CartRepositoryInterface $cartRepository
     * @param ProductHelper $productHelper
     * @param QuoteFactory $quoteFactory
     * @param AbstractResponseInterfaceFactory $abstractResponse
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param StoreManager $storeManager
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        CartInterfaceFactory $cartFactory,
        CustomerHelper $customerHelper,
        ProductRepositoryInterface $productRepository,
        EavAttribute $eavAttribute,
        CartRepositoryInterface $cartRepository,
        ProductHelper $productHelper,
        QuoteFactory $quoteFactory,
        AbstractResponseInterfaceFactory $abstractResponse,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        StoreManager $storeManager
    )
    {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->cartFactory = $cartFactory;
        $this->customerHelper = $customerHelper;
        $this->productRepository = $productRepository;
        $this->eavAttribute = $eavAttribute;
        $this->cartRepository = $cartRepository;
        $this->productHelper = $productHelper;
        $this->quoteFactory = $quoteFactory;
        $this->abstractResponse = $abstractResponse;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @return \Codilar\Checkout\Api\Data\CartInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCart()
    {
        /** @var CartInterface $cart */
        $cart = $this->cartFactory->create();
        $quote = $this->customerHelper->getActiveQuote();
        $this->collectTotals($quote);
        return $this->sendResponse($cart->init($quote));
    }

    /**
     * @param int $itemId
     * @return \Codilar\Checkout\Api\Data\CartInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeItem($itemId)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->customerHelper->getActiveQuote();
        if (!$quote->getItemById($itemId)) {
            throw new LocalizedException(__("Could not delete cart item"));
        }
        $quote->removeItem($itemId);
        $this->collectTotals($quote);
        $this->cartRepository->save($quote);
        /** @var CartInterface $cart */
        $cart = $this->cartFactory->create();
        return $this->sendResponse($cart->init($quote));
    }

    /**
     * @param \Codilar\Checkout\Api\Data\ItemInterface $item
     * @return \Codilar\Checkout\Api\Data\CartInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addItem($item)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->customerHelper->getActiveQuote();
        $quote->setRemoteIp($item->getRemoteIp());
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->productRepository->get($item->getSku(), false, 1);
        $customOptions = [];
        $superAttributes = [];

        foreach ($item->getCustomOptions() as $customOption) {
            $customOption->setOptionValue(array_filter($customOption->getOptionValue()));
            if (count($customOption->getOptionValue())) {
                $customOptions[$customOption->getOptionId()] = $customOption->getOptionValue();
            }
        }
        if ($product->getHasOptions()) {
            foreach ($product->getOptions() as $option) {
                if (!array_key_exists($option->getOptionId(), $customOptions)) {
                    if ($this->productHelper->getProductOptionType($option) == 'select') {
                        $customOptions[$option->getOptionId()] = [];
                    } else {
                        $customOptions[$option->getOptionId()] = '';
                    }
                } else {
                    if ($this->productHelper->getProductOptionType($option) !== 'select') {
                        $customOptions[$option->getOptionId()] = reset($customOptions[$option->getOptionId()]);
                    }
                }
            }
        }

        foreach ($item->getConfigurableOptions() as $configurableOption) {
            $attributeId = $this->eavAttribute->getIdByCode(\Magento\Catalog\Model\Product::ENTITY, $configurableOption->getAttributeCode());
            if ($attributeId) {
                $superAttributes[$attributeId] = $configurableOption->getId();
            }
        }
        $request = new \Magento\Framework\DataObject();
        $request->setData([
            'qty' => $item->getQty(),
            'product'   =>  $product->getId(),
            'item'  =>  $product->getId(),
            'selected_configurable_option' => null,
            'related_product' => null,
            'options' => $customOptions,
            'super_attribute' => $superAttributes
        ]);

        $this->processAdditionalOptions($product, $item);

        $response = $quote->addProduct($product, $request);
        if (!$response instanceof \Magento\Quote\Model\Quote\Item) {
            throw new LocalizedException(__($response));
        }
        $this->collectTotals($quote);
        $this->cartRepository->save($quote);
        /** @var CartInterface $cart */
        $cart = $this->cartFactory->create();
        return $this->sendResponse($cart->init($quote));
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param ItemInterface $item
     */
    protected function processAdditionalOptions($product, $item)
    {
        $allowedAdditionalOptions = ["Comments"];
        $additionalOptions = [];
        foreach ($item->getAdditionalOptions() as $additionalOption) {
            if (in_array($additionalOption->getLabel(), $allowedAdditionalOptions) && strlen($additionalOption->getValue()) > 0) {
                $additionalOptions[] = [
                    'label' => $additionalOption->getLabel(),
                    'value' => $additionalOption->getValue()
                ];
            }
        }
        $product->addCustomOption('additional_options', \json_encode($additionalOptions));
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @return void
     */
    protected function collectTotals($quote)
    {
        $quote->getBillingAddress();
        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->setTotalsCollectedFlag(false);
        $quote->collectTotals();
    }

    /**
     * @param string $cartId
     * @return \Codilar\Checkout\Api\Data\CartInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeCoupon($cartId)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->customerHelper->getActiveQuote();
//        if ($quote->getId() == $cartId) {
            try {
                $quote->setCouponCode('')
                    ->collectTotals()
                    ->save();
            } catch (\Exception $e) {
            }
//        }
        /** @var CartInterface $cart */
        $cart = $this->cartFactory->create();
        return $this->sendResponse($cart->init($quote));
    }

    /**
     * @param \Codilar\Checkout\Api\Data\MergeCartInterface $mergeData
     * @return \Codilar\Customer\Api\Data\AbstractResponseInterface
     */
    public function mergeCart($mergeData)
    {
        $this->storeManager->setCurrentStore(1);
        /** @var \Codilar\Customer\Api\Data\AbstractResponseInterface $response */
        $response = $this->abstractResponse->create();
        /** @var \Magento\Quote\Model\Quote $quote */
        $quoteMasked = $this->quoteIdMaskFactory->create()->load($mergeData->getQuoteId(), 'masked_id');
        try {
            $quote = $this->cartRepository->get($quoteMasked->getQuoteId());
            if ($quote->getId()) {
                try {
                    /** @var \Magento\Quote\Model\Quote $customerQuote */
                    $customerQuote = $this->customerHelper->getActiveQuote();
                    if ($customerQuote->getIsActive() && ($quote->getCustomerId() == null)) {
                        $customerQuote->merge($quote);
                        $customerQuote->collectTotals();
                        $this->cartRepository->save($customerQuote);
                        $this->cartRepository->delete($quote);
                        $response->setStatus(true)->setMessage("Cart is updated");
                    } else {
                        throw new LocalizedException(__("Can't Merge"));
                    }
                } catch (LocalizedException $e) {
                    $response->setStatus(false)->setMessage("Can't Merge");
                }
            } else {
                $response->setStatus(false)->setMessage("Can't Merge");
            }
        } catch (Exception $exception) {
            $response->setStatus(false)->setMessage("Can't Merge");
        }
        return $response;
    }
}