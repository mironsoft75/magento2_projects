<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\ProductsInCart\Block\Adminhtml\Edit\Tab\View;

use Magento\Customer\Controller\RegistryConstants;

class ProductsInCart extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Sales reorder
     *
     * @var \Magento\Sales\Helper\Reorder
     */
    protected $salesReorder = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var  \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $collectionFactory
     * @param \Magento\Sales\Helper\Reorder $salesReorder
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Quote\Api\CartRepositoryInterface $cartRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $collectionFactory,
        \Magento\Sales\Helper\Reorder $salesReorder,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->salesReorder = $salesReorder;
        $this->collectionFactory = $collectionFactory;
        $this->cartRepository = $cartRepository;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('customer_orders_products_in_cart_grid');
        $this->setDefaultSort('created_at', 'desc');
        $this->setUseAjax(true);
    }

    /**
     * Apply various selection filters to prepare the Quote Item collection.
     *
     * @return $this
     * @throws \Exception
     */
    protected function _prepareCollection()
    {
        $collection = $this->collectionFactory->getReport('quote_item_grid_data_source')
                        ->addFieldToSelect('quote_id')
                        ->addFieldToSelect('name')
                        ->addFieldToSelect('sku')
                        ->addFieldToSelect('qty')
                        ->addFieldToSelect('price')
                        ->addFieldToSelect('created_at')
                        ->addFieldToFilter(
                            'quote_id',
                            $this->cartRepository->getForCustomer(
                                $this->getCustomerId()
                            )->getId()
                        );

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @inheritdoc
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'sku',
            ['header' => __('SKU'), 'width' => '100', 'index' => 'sku']
        );
        $this->addColumn(
            'name',
            ['header' => __('Name'), 'width' => '100', 'index' => 'name']
        );
        $this->addColumn(
            'qty',
            ['header' => __('Quantity'), 'width' => '100', 'index' => 'qty']
        );
        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'index' => 'price',
                'type' => 'currency',
                'currency' => 'quote_currency_code',
                'rate'  => 1
            ]
        );
        $this->addColumn(
            'created_at',
            ['header' => __('Created At'), 'width' => '100', 'index' => 'created_at']
        );

        $this->addColumn(
            'created_at',
            ['header' => __('Added On'), 'index' => 'created_at', 'type' => 'datetime']
        );

        return parent::_prepareColumns();
    }

    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }
}
