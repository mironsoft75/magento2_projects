<?php
namespace Softprodigy\Bluedart\Block\Adminhtml\Sales\Order;
 

/**
 * Adminhtml sales order view
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class View extends \Magento\Sales\Block\Adminhtml\Order\View {
    protected $__helper;
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Model\Config $salesConfig
     * @param \Magento\Sales\Helper\Reorder $reorderHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Model\Config $salesConfig,
        \Magento\Sales\Helper\Reorder $reorderHelper,
        \Softprodigy\Bluedart\Helper\Data $__helper,
        array $data = []
    ) {
        $this->__helper = $__helper;
        parent::__construct($context, $registry, $salesConfig, $reorderHelper, $data);
    }
    /**
     * Constructor
     *
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _construct() {
        parent::_construct();
        //-------------Customize Code -------------------
        $_bHelper = $this->__helper; 
        //die('herer');
        if (1===$_bHelper->getIsEnabled()) {
            if ($this->getOrder()->canShip() && !$this->getOrder()->getForcedShipmentWithInvoice()){
				$file_name = 'order_' . $this->getOrder()->getRealOrderId() . '.pdf';
                $filename = $_bHelper->getDirPath('media') . "/bluredart_pdf/" . $file_name;
                $listRowId = $_bHelper->hasOrderAwbById($this->getOrder()->getId());
                //print_r($listRowId); echo '--yss---';
                if (!empty($listRowId)) {
                    $url = $_bHelper->getMediaUrl(). "bluredart_pdf/" . $file_name;
                    //$url = $this->getUrl('bluedart/download/awbpdf', ['rid'=>$listRowId]);
                    //print_r($url); 
                    $this->buttonList->add('bluedart_ship', 
                            [
                                'label' => __('Download Bluedart Shipment PDF'),
                                'onclick' => "window.open('$url', '_blank')",
                                'class' => 'go'
                            ]);
                } else {
						$this->buttonList->add('bluedart_ship', 
                            [
                                'label' => __('Send Bluedart Shipment'),
                                'onclick' => 'getpop()',
                                'class' => 'go'
                            ]);
                }
            }
            if($this->getOrder()->hasShipments()){
                $file_name = 'order_' . $this->getOrder()->getRealOrderId() . '.pdf';
                $filename = $_bHelper->getDirPath('media') . "/bluredart_pdf/" . $file_name;
                $listRowId = $_bHelper->hasOrderAwbById($this->getOrder()->getId());
                if (!empty($listRowId)) {
                    $url = $_bHelper->getMediaUrl() . "bluredart_pdf/" . $file_name;
                    $this->buttonList->add(
                        'bluedart_ship',
                        [
                            'label' => __('Download Bluedart Shipment PDF'),
                            'onclick' => "window.open('$url', '_blank')",
                            'class' => 'go'
                        ]
                    );
                }
            }
        }
    }
 
    
}
