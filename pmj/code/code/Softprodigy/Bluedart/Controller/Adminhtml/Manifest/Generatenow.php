<?php

namespace Softprodigy\Bluedart\Controller\Adminhtml\Manifest;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Softprodigy\Bluedart\Model\Awblist;
use Softprodigy\Bluedart\Model\Manifest;
use Magento\Sales\Model\Order as salesOrder;

/**
 * Description of Generate
 *
 * @author mannu
 */
class Generatenow extends \Magento\Backend\App\Action {

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    protected $awbList;
    protected $__helper;
    protected $ordermodel;
    protected $manifestModel;
    protected $messageManager;
    
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
    Context $context, PageFactory $resultPageFactory, 
            Manifest $manifestModel,
            salesOrder $ordermodel, Awblist $awbList, \Magento\Framework\Message\ManagerInterface $messageManager, \Softprodigy\Bluedart\Helper\Data $__helper
    ) {

        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->awbList = $awbList;
        $this->__helper = $__helper;
        $this->ordermodel = $ordermodel;
        $this->messageManager = $messageManager;
        $this->manifestModel = $manifestModel;
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Softprodigy_Bluedart::Bluedart');
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute() {
        $picupTime = $this->__helper->getStoreConfig("Softprodigy_Bluedart/general/pickup_time");
        $storename = $this->__helper->getStoreConfig("Softprodigy_Bluedart/general/store_name");
        $storeAddr = $this->__helper->getStoreConfig("Softprodigy_Bluedart/general/store_contact_addr");
        $storeAddr = implode(", ", explode("\n", $storeAddr));

        $startFrom = trim($this->getRequest()->getParam('mfrom', ''));
        $upto = trim($this->getRequest()->getParam('mto', ''));

        if (!empty($startFrom)) {
            $startFrom = date('Y-m-d H:i:s', strtotime($startFrom . ' ' . $picupTime));
        }

        if (!empty($upto)) {
            $upto = date('Y-m-d H:i:s', strtotime($upto . ' ' . $picupTime));
        }

        $orderCount = 0;
        $manifestno = time();
       // var_Dump($this->getRequest()->getParams());
        //die("sdf");
        if (!empty($startFrom) && !empty($upto)) {

            $collection = $this->awbList->getCollection();
            $collection->getSelect()->where("awb_date BETWEEN '" . $startFrom . "' AND '" . $upto . "'");
            //$orderIds = $collection->getColumnValues('order_increment_id');
            // $orderCollection = $this->ordermodel->getCollection();
            //$orderCollection->addFieldToFilter('increment_id',['in'=>$orderIds]);

            $html = '';
            $baseurl = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getBaseUrl();
            //first head table.
            
            $html .= '<table width="100%" cellspacing="0" cellpadding="2" border="0" valign="middle" ><tbody><tr>'
                    . '<td>Generated For: '.$startFrom.' -  '.$upto.'</td>'
                    . '<td>Date: ' . date('Y-m-d') . '</td>'
                    . '</tr></tbody></table>'
                    . '<table cellspacing="0" cellpadding="2" border="1" valign="middle"><tbody>'
                    . '<tr>'
                    . '<td>' . __("Manifest sheet for : %1", $baseurl) . '</td>'
                    . '<td>' . __("Courier : %1", "BLUEDART") . '</td>'
                    . '<td>' . __("Manifest No") . ': ' . $manifestno . '</td>'
                    . '<td>' . __("Channel Name") . ':' . $storename . '</td>'
                    . '</tr>'
                    . '</tbody></table>';

            $html .= '<br/><p style="text-align:center;">' . html_entity_decode($storeAddr) . '</p><br/>';

            $html .= '<table style="text-align: center;" cellspacing="0" cellpadding="2" border="1" valign="middle">'
                    . '<thead>'
                    . '<tr>'
                    . '<th>Sr</th>'
                    . '<th>Airwaybill</th>'
                    . '<th>Reference Number</th>'
                    . '<th>Attention</th>'
                    . '<th>City/State</th>'
                    . '<th>Contents</th>'
                    . '<th>Weight(Kg)</th>'
                    . '<th>Collectable</th>'
                    . '<th>Mode</th>'
                    . '<th>Barcode</th>'
                    . '</tr>'
                    . '</thead>';
            $html .= '<tbody>';
            $inc = 1;
            
            foreach ($collection as $_itm) {
                $oitem = '';
                $oitem = $this->ordermodel->load($_itm->getOrderIncrementId(), 'increment_id');

                $collect = '';
                $mode = '';
                if ($oitem->getMethod() == \Magento\OfflinePayments\Model\Cashondelivery::PAYMENT_METHOD_CASHONDELIVERY_CODE) {
                    $collect = $oitem->getGrandTotal();
                    $mode = 'COD';
                }
                $orderCount++;
                $html .= '<tr>'
                        . '<td>' . $inc . '</td>'
                        . '<td>' . $_itm->getAwbNumber() . '</td>'
                        . '<td>' . $_itm->getOrderIncrementId() . '</td>'
                        . '<td>' . $oitem->getCustomerFirstname() . ' ' . $oitem->getCustomerLastname() . '</td>'
                        . '<td>' . $_itm->getCityState() . '</td>'
                        . '<td>' . $_itm->getProductDetails() . '</td>'
                        . '<td>' . $_itm->getAwbWeight() . '</td>'
                        . '<td>' . $collect . '</td>'
                        . '<td>' . $mode . '</td>'
                        . '<td>'
                        . '<div class="img-cntr"><barcode code=' . $_itm->getAwbNumber() . ' type="C39" size="1.0" height="2.0" /></div>'
                        . '<p style="width:100%; text-align:center;">' . $_itm->getAwbNumber() . '</p>'
                        . '</td>'
                        . '<tr>';
                $inc++;
            }
            if($orderCount>0){
                $html .= '</tbody>';
                $html .= '</table>';
                //var_dump($html); die;
                //require ('Softprodigy/MPDF6/mPDF.php');
                require('Softprodigy/mpdf_vendor/autoload.php');
                $mpdf = new \mPDF('c', 'A4', '', '', 15, 15, 16, 16, 9, 9);
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->showImageErrors = true;

                $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list   

                $html2 = '<html>
                        <head>
                        <meta charset="utf-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <title></title>
                        <meta name="description" content="">

                        </head>
                        <body style="margin:0; padding: 0;">';
                $html2 .= $html;
                $html2 .= '</body>'
                        . '</html>';

                $mpdf2 = clone $mpdf;
                $mpdf->WriteHTML($html2, 2);
                $mpdf2->WriteHTML($html2, 2);
                if (!is_dir($this->__helper->getDirPath('media') . 'bluredart_pdf/')) {
                    mkdir($this->__helper->getDirPath('media') . 'bluredart_pdf/', 0777);
                }
                $file_name = 'manifest_' . $manifestno . '.pdf';
                $filename = $this->__helper->getDirPath('media') . "bluredart_pdf/" . $file_name;
                $mpdf2->Output($filename, 'F');

                $mancoll = $this->manifestModel->getCollection();

                $mancoll->addFieldToFilter('gen_from',['datetime'=>$startFrom]);
                $mancoll->addFieldToFilter('gen_to',['datetime'=>$upto]);

                $hasMItem = $mancoll->getFirstItem();

                $this->manifestModel->setData([
                    'order_count'=>$orderCount,
                    'batch_number'=>$manifestno,
                    'file_name'=>$file_name,
                    'gen_from'=>$startFrom,
                    'gen_to'=>$upto,
                    'created_on'=>date('Y-m-d H:i:s')
                ]);
                if($hasMItem->getId()){
                    $this->manifestModel->setId($hasMItem->getId());
                } 
                $this->manifestModel->save();

                $mpdf->Output($file_name, 'D');
            }else{
                $this->messageManager->addError('Sorry! we could not find any awb to generate manifest for dates between '.$startFrom.' and '.$upto);
                $this->_redirect('bluedart/manifest/generate');
            }
            
            /*             * ******End Genarating PDF******** */
        }
    }

}
