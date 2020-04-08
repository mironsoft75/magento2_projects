<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Model\Data\Cart;

use Codilar\Checkout\Api\Data\Cart\Item\OptionInterface;
use Codilar\Checkout\Api\Data\Cart\Item\OptionInterfaceFactory;
use Codilar\Checkout\Api\Data\Cart\ItemInterface;
use Codilar\Core\Helper\Product as ProductHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Product\Configuration as ItemConfigurationHelper;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\Framework\Exception\LocalizedException;

class Item implements ItemInterface
{
    /**
     * @var OptionInterfaceFactory
     */
    private $optionFactory;
    /**
     * @var ItemConfigurationHelper
     */
    private $itemConfigurationHelper;
    /**
     * @var ProductHelper
     */
    private $productHelper;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * Item constructor.
     * @param OptionInterfaceFactory $optionFactory
     * @param ItemConfigurationHelper $itemConfigurationHelper
     * @param ProductHelper $productHelper
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        OptionInterfaceFactory $optionFactory,
        ItemConfigurationHelper $itemConfigurationHelper,
        ProductHelper $productHelper,
        ProductRepositoryInterface $productRepository
    ) {
        $this->optionFactory = $optionFactory;
        $this->itemConfigurationHelper = $itemConfigurationHelper;
        $this->productHelper = $productHelper;
        $this->productRepository = $productRepository;
    }

    /**
     * @var \Magento\Quote\Model\Quote\Item
     */
    private $item = null;

    /**
     * @return int
     * @throws LocalizedException
     */
    public function getProductId()
    {
        return (int)$this->getItem()->getProductId();
    }

    /**
     * @return int
     * @throws LocalizedException
     */
    public function getItemId()
    {
        return $this->getItem()->getItemId();
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getName()
    {
        return $this->getItem()->getName();
    }

    /**
     * @return float
     * @throws LocalizedException
     */
    public function getPrice()
    {
        $regularPrice = $this->getItem()->getProduct()->getPrice();
        $finalPrice = $this->getSpecialPrice();
        return $regularPrice > $finalPrice ? $regularPrice : $finalPrice;
    }

    /**
     * @return float
     * @throws LocalizedException
     */
    public function getSpecialPrice()
    {
        return $this->getItem()->getProduct()->getFinalPrice();
    }

    /**
     * @return float
     * @throws LocalizedException
     */
    public function getDiscountPercent()
    {
        $price = $this->getPrice();
        $specialPrice = $this->getSpecialPrice();
        $discount = round(
            (($price - $specialPrice) * 100 / $price),
            2
        );
        return $discount > 0 ? $discount : 0;
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getImage()
    {
        return $this->productHelper->getImageUrl($this->getItem()->getProduct(), 'minicart_product_image');
    }

    /**
     * @return \Codilar\Checkout\Api\Data\Cart\Item\OptionInterface[]
     * @throws LocalizedException
     */
    public function getOptions()
    {
        $options = [];
        $item = $this->getItem();
        $options[] = $this->getNewOption(__("Quantity"), $item->getQty());
        foreach ($this->itemConfigurationHelper->getOptions($this->getItem()) as $option) {
            $options[] = $this->getNewOption($option['label'], htmlspecialchars_decode($option['value'], ENT_QUOTES));
        }
        return $options;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item $item
     * @return $this
     */
    public function init($item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return \Magento\Quote\Model\Quote\Item
     * @throws LocalizedException
     */
    protected function getItem()
    {
        if (!$this->item instanceof \Magento\Quote\Model\Quote\Item) {
            throw new LocalizedException(__("Cart item not initialized"));
        }
        return $this->item;
    }

    /**
     * @param string $label
     * @param string $value
     * @return OptionInterface
     */
    protected function getNewOption($label, $value)
    {
        /** @var OptionInterface $option */
        $option = $this->optionFactory->create();
        return $option->setLabel($label)->setValue($value);
    }

    /**
     * @return boolean
     * @throws LocalizedException
     */
    public function getStockStatus()
    {
        $productId = $this->getProductId();
        if ($this->getItem()->getProductType() === "configurable") {
            $productId = $this->productRepository->get($this->getItem()->getSku())->getId();
        }
        /** @var StockItemInterface $stock */
        $stock = $this->getItem()->getProduct()->getExtensionAttributes()->getStockItem();
        return $stock->getIsInStock() && $stock->getQty() > 0;
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getUrlKey()
    {
        $product = $this->getItem()->getProduct();
        return $product->getUrlKey();
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getSku()
    {
        $product = $this->getItem()->getProduct();
        return $product->getSku();
    }
}
