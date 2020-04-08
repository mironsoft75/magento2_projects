<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Block\Adminhtml\Sales\Items\Column;


use Codilar\DynamicForm\Helper\Response;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Block\Adminhtml\Items\Column\DefaultColumn;

class CustomForms extends DefaultColumn
{
    /**
     * @var Response
     */
    private $formResponseHelper;

    /**
     * CustomForms constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\Product\OptionFactory $optionFactory
     * @param Response $formResponseHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\CatalogInventory\Api\StockConfigurationInterface $stockConfiguration,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Product\OptionFactory $optionFactory,
        Response $formResponseHelper,
        array $data = []
    )
    {
        parent::__construct($context, $stockRegistry, $stockConfiguration, $registry, $optionFactory, $data);
        $this->formResponseHelper = $formResponseHelper;
    }

    /**
     * @return \Magento\Framework\DataObject[]
     */
    public function getResponses()
    {
        $item = $this->getItem();
        $order = $item->getOrder();
        $product = $item->getProduct();
        $forms = [];
        if ($product) {
            $formIds = explode(',', $product->getData('custom_forms'));
            foreach ($formIds as $formId) {
                try {
                    $forms[] = $this->formResponseHelper->getFilledResponse($formId, $order->getIncrementId(), 'order_id', true);
                } catch (NoSuchEntityException $e) {
                    continue;
                }
            }
        }
        return $forms;
    }
}