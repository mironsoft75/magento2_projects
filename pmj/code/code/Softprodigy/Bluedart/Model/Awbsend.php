<?php

namespace Softprodigy\Bluedart\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Psr\Log\LoggerInterface as Logger;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Softprodigy\Bluedart\Model\Mail\TransportBuilder;
use Magento\Sales\Model\Order\Email\Sender\ShipmentSender;

/**
 * Description of Awbthread
 *
 * @author mannu
 */
class Awbsend {

    private $param;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     *
     * @var Softprodigy\Bluedart\Helper\Data 
     */
    protected $__helper;
    protected $messageManager;
    protected $transportBuilder;
    protected $orderModel;

    /**
     * @var \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader
     */
    protected $shipmentLoader;

    /**
     * @var ShipmentSender
     */
    protected $shipmentSender;
    protected $_authorization;
    protected $_objectManager;

    public function __construct(
    PageFactory $resultPageFactory, \Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader $shipmentLoader, ShipmentSender $shipmentSender, \Magento\Sales\Model\Order $orderModel, \Magento\Framework\Message\ManagerInterface $messageManager, \Softprodigy\Bluedart\Helper\Data $__helper, Logger $logger, TransportBuilder $transportBuilder
    ) {

        $this->__helper = $__helper;
        $this->messageManager = $messageManager;
        $this->resultPageFactory = $resultPageFactory;
        $this->logger = $logger;
        $this->transportBuilder = $transportBuilder;
        $this->orderModel = $orderModel;
        $this->shipmentLoader = $shipmentLoader;
        $this->shipmentSender = $shipmentSender;
    }

    public function authHandler($auth, $objM) {
        $this->_authorization = $auth;
        $this->_objectManager = $objM;
        return $this;
    }

    public function setParam($param) {
        $this->param = $param;
        return $this;
    }

    public function resetParam() {
        unset($this->param);
        return $this;
    }

    /**
     * Save shipment and order in one transaction
     *
     * @param \Magento\Sales\Model\Order\Shipment $shipment
     * @return $this
     */
    protected function _saveShipment($shipment) {
        $shipment->getOrder()->setIsInProcess(true);
        $transaction = $this->_objectManager->create(
                'Magento\Framework\DB\Transaction'
        );
        $transaction->addObject(
                $shipment
        )->addObject(
                $shipment->getOrder()
        )->save();

        return $this;
    }

    public function process() {
        if ($this->param and is_array($this->param)) {
            try {
                $errors = [];
                ini_set('mysql.connect_timeout', 3600);
                ini_set("default_socket_timeout", 6000);

                $param = $this->param; //$this->getRequest()->getParams();
                $helper = $this->__helper;

                if ($helper->getStoreConfig('Softprodigy_Bluedart/general/enabled')) {
                    $order = $this->orderModel->load($param['order_id']); //load order by order id 

                    $shipping_address = $order->getShippingAddress();
                    if (!$shipping_address or empty($shipping_address)) {
                        throw new \Exception("Sorry! Could not send consignment for this order");
                    }
                    $shipping_address->getTelephone();
                    $shipping_address->getPostcode();
                    $cust_name = $shipping_address->getFirstname() . ' ' . $shipping_address->getLastname();
                    $order->getIncrementId();
                    $payment_method_code = $order->getPayment()->getMethodInstance()->getCode(); //cashondelivery


                    $collectableAmount = '';
                    $SubProductCode = 'p';
                    $pdf_method = "PREPAID ORDER";
                    if ($payment_method_code == 'cashondelivery') {
                        $collectableAmount = $order->getGrandTotal();
                        $SubProductCode = 'c';
                        $pdf_method = "CASH ON DELIVERY (COD)";
                    }
                    $product_code = $helper->getStoreConfig('Softprodigy_Bluedart/general/product_code');
                    $shipmode = $param['shipmode'];
                    if (in_array($shipmode, array('A', 'E'))) {
                        $product_code = $shipmode;
                    }
                    //echo '<pre>';
                    //echo 'col - '.$collectableAmount; 
                    $ordered_items = $order->getAllVisibleItems();
                    $mrp = 0;
                    $commodityDetail = array();
                    $i = 1;
                    $qty = 0;
                    //$weight = 10;
                    $specialInstruction = '';
                    $orItems = [];
                    foreach ($ordered_items as $item) {
                        if ($item->getParentItemId()) {
                            //$item = $item->getParentItem();
                        }

                        $item->getItemId(); //product id     
                        //var_dump($item->getProductId());  die;
                        $item->getSku();
                        $qty = $qty + $item->getQtyOrdered(); //ordered qty of item     
                        $mrp = $mrp + $item->getprice();
                        $orItems['items'][$item->getItemId()] = $item->getQtyOrdered();
                        //var_dump($item->getWeight()); die;
                        //$weight += ($item->getWeight() * $item->getQtyOrdered());
                        //$commodityDetail['CommodityDetail'.$i] =  preg_replace('/[^a-zA-Z0-9]/', ' ', $item->getName());
                        $commodityDetail['CommodityDetail' . $i] = preg_replace('/[^a-zA-Z0-9]/', ' ', substr($item->getName(), 0, 30));
                        //$specialInstruction = $commodityDetail['CommodityDetail' . $i] . ' ' . $specialInstruction;
                        $i++;
                    }

                    //var_dump($weight); die;
                    $blueAddress = ($helper->getStoreConfig('Softprodigy_Bluedart/general/store_contact_addr'));
                    //$line_store_address = explode("\n", $blueAddress);
                    $line_store_address = $blueAddress;
                    $line_store_address_1 = substr($line_store_address, 0, 30);
                    $line_store_address_2 = substr($line_store_address, 30, 30);
                    $line_store_address_3 = substr($line_store_address, 60, 30);
                    $line_store_address_4 = substr($line_store_address, 90, 30);
                    $line_store_address_5 = substr($line_store_address, 120, 30);

                    $bluedartKey = $helper->getStoreConfig('Softprodigy_Bluedart/general/licence_key');
                    $loginId = $helper->getStoreConfig('Softprodigy_Bluedart/general/login_id');

                    $storeName = $helper->getStoreConfig('Softprodigy_Bluedart/general/store_name');
                    $email = $helper->getStoreConfig('Softprodigy_Bluedart/general/email_id_from');
                    $storePhone = $helper->getStoreConfig('Softprodigy_Bluedart/general/contact_number');
                    $storePincode = $helper->getStoreConfig('Softprodigy_Bluedart/general/pin_code');
                    $customercode = $helper->getStoreConfig('Softprodigy_Bluedart/general/customer_code');
                    $vandercode = $helper->getStoreConfig('Softprodigy_Bluedart/general/vander_code');
                    $originarea = $helper->getStoreConfig('Softprodigy_Bluedart/general/origin_area');
                    $tin = $helper->getStoreConfig('Softprodigy_Bluedart/general/tin_no');


                    $pickup_time = $helper->getStoreConfig('Softprodigy_Bluedart/general/pickup_time');

                    $dimension_breadth = $param['shipment']['breadth'];
                    $dimension_height = $param['shipment']['height'];
                    $dimension_length = $param['shipment']['length'];
                    $actualWeight = $param['shipment']['weight'];

                    $actualWeight = number_format(floatval($actualWeight) / 1000, 4);
                    $dimension = $dimension_length . '*' . $dimension_breadth . '*' . $dimension_height;

                    if ($collectableAmount)
                        $mrp = $collectableAmount;

                    /* -------- Start Blue Dart API--------- */

                    if ($helper->getStoreConfig('Softprodigy_Bluedart/general/sandbox') == 1)
                        $ApiUrl = 'http://netconnect.bluedart.com/ver1.7/Demo/ShippingAPI/WayBill/WayBillGeneration.svc'; //-----For Sandbox-----
                    else
                        $ApiUrl = 'http://netconnect.bluedart.com/ver1.7/ShippingAPI/WayBill/WayBillGeneration.svc'; //----For Live----




                        
//echo $ApiUrl . '?wsdl';

                    $soap = new \Softprodigy\Bluedart\Controller\DebugSoapClient($ApiUrl . '?wsdl', array(
                        'trace' => 1,
                        'style' => SOAP_DOCUMENT,
                        'use' => SOAP_LITERAL,
                        'soap_version' => SOAP_1_2
                    ));

                    $soap->__setLocation($ApiUrl);

                    $soap->sendRequest = true;
                    $soap->printRequest = false;
                    $soap->formatXML = true;

                    $actionHeader = new \SoapHeader('http://www.w3.org/2005/08/addressing', 'Action', 'http://tempuri.org/IWayBillGeneration/GenerateWayBill', true);
                    $soap->__setSoapHeaders($actionHeader);
                    #echo "end of Soap 1.2 version (WSHttpBinding)  setting";
                    $street = $shipping_address->getStreet();

                    $invoiceIncrementID = '';
                    if ($order->hasInvoices()) {
                        foreach ($order->getInvoiceCollection() as $inv) {
                            $invoiceIncrementID = $inv->getIncrementId();
                            //$invDate = date('d-M-Y', strtotime($inv->getCreatedAt()));
                        }
                    }

                    $curTime = time();
                    $curPickTime = strtotime(date('Y-m-d') . " " . $pickup_time) - 600;
                    $pickDate = date('Y-m-d');
                    $pickTime = date('H:i', strtotime(date('Y-m-d') . " " . $pickup_time));
                    //reducing 10 min time 
                    //date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($startTime)));
                    if ($curTime < $curPickTime) {
                        $pickDate = date('Y-m-d', strtotime('+1 day'));
                    }

                    $street = $shipping_address->getStreet();
                    $street = implode(" ", $street);
                    $street1 = substr($street, 0, 30);
                    $street2 = substr($street, 30, 30);
                    $street3 = substr($street, 60, 30);

                    $params = array(
                        'Request' =>
                        array(
                            'Consignee' =>
                            array(
                                'ConsigneeAddress1' => $street1,
                                'ConsigneeAddress2' => $street2,
                                'ConsigneeAddress3' => $street3,
                                'ConsigneeAttention' => '',
                                'ConsigneeMobile' => $shipping_address->getTelephone(),
                                'ConsigneeName' => $cust_name,
                                'ConsigneePincode' => $shipping_address->getPostcode(),
                                'ConsigneeTelephone' => $shipping_address->getTelephone(),
                            ),
                            'Services' =>
                            array(
                                'ActualWeight' => $actualWeight,
                                'CollectableAmount' => $collectableAmount,
                                'Commodity' => $commodityDetail,
                                'CreditReferenceNo' => $order->getIncrementId(),
                                'DeclaredValue' => $mrp,
                                'Dimensions' =>
                                array(
                                    'Dimension' =>
                                    array(
                                        'Breadth' => $dimension_breadth,
                                        'Count' => '1',
                                        'Height' => $dimension_height,
                                        'Length' => $dimension_length
                                    ),
                                ),
                                'InvoiceNo' => $invoiceIncrementID,
                                'PackType' => '',
                                'PickupDate' => $pickDate,
                                'PickupTime' => $pickTime, //(optional)
                                'PieceCount' => '1', //(#default)
                                'ProductCode' => $product_code, //(#default)
                                'ProductType' => 'Dutiables', //(#default)
                                'SpecialInstruction' => $specialInstruction,
                                'SubProductCode' => $SubProductCode, //(for prepaid ordered it will p & #for COD it will be c)
                            ),
                            'Shipper' =>
                            array(
                                'CustomerAddress1' => $line_store_address_1,
                                'CustomerAddress2' => $line_store_address_2,
                                'CustomerAddress3' => $line_store_address_3,
                                'CustomerAddress4' => $line_store_address_4,
                                'CustomerAddress5' => $line_store_address_5,
                                'CustomerCode' => $customercode,
                                'CustomerEmailID' => $email,
                                'CustomerMobile' => $storePhone,
                                'CustomerName' => $storeName,
                                'CustomerPincode' => $storePincode,
                                'CustomerTelephone' => $storePhone,
                                'IsToPayCustomer' => '',
                                'OriginArea' => $originarea,
                                'Sender' => '',
                                'VendorCode' => $vandercode
                            ),
                            'SubShipper' =>
                            array(
                                'CustomerAddress1' => $line_store_address_1,
                                'CustomerAddress2' => $line_store_address_2,
                                'CustomerAddress3' => $line_store_address_3,
                                'CustomerAddress4' => $line_store_address_4,
                                'CustomerAddress5' => $line_store_address_5,
                                'CustomerCode' => $customercode,
                                'CustomerEmailID' => $email,
                                'CustomerMobile' => $storePhone,
                                'CustomerName' => $storeName,
                                'CustomerPincode' => $storePincode,
                                'CustomerTelephone' => $storePhone,
                                'IsToPayCustomer' => '',
                                'OriginArea' => $originarea,
                                'Sender' => '',
                                'VendorCode' => $vandercode
                            )
                        ),
                        'Profile' =>
                        array(
                            'Api_type' => 'S',
                            'LicenceKey' => $bluedartKey,
                            'LoginID' => $loginId,
                            'Version' => '1.3')
                    );

                    //echo "Before";
                    // Here I call my external function
                    $result = $soap->__soapCall('GenerateWayBill', array($params));

                    /* echo "Generated Waybill number " + $result->GenerateWayBillResult->AWBNo;
                      echo "<br>";
                      echo $result->GenerateWayBillResult->Status->WayBillGenerationStatus->StatusInformation ;

                      echo "<br>";
                      echo '<h2>Result</h2><pre>'; print_r($result); die; //echo '</pre>';die; */

                    $data = $result->GenerateWayBillResult->AWBPrintContent;
                    $error = $result->GenerateWayBillResult->IsError;

                    if (!empty($error)) {

                        $check_err = $result->GenerateWayBillResult->Status->WayBillGenerationStatus; //->StatusInformation ;

                        if (is_array($check_err)) {
                            $k = 1;
                            $error_msg = '';

                            if (count($check_err) > 0)
                                $error_msg .= '<ul>';

                            foreach ($check_err as $err) {
                                $error_msg .='<li>' . $err->StatusInformation . '</li>';
                            }

                            if (count($check_err) > 0)
                                $error_msg .= '</ul>';
                        } else
                            $error_msg = $result->GenerateWayBillResult->Status->WayBillGenerationStatus->StatusInformation;

                        if (!empty($error_msg))
                            $errors['error'] = $error_msg;
                    }
                    else {
                        /*                         * ******Genarating PDF******** */
                        
                        /*$mpdf = new \Softprodigy_MPDF6_mPDF('c', 'A4', '', '', 32, 25, 27, 25, 16, 13);
                        $mpdf->SetDisplayMode('fullpage');
                        $mpdf->showImageErrors = true;

                        $mpdf->list_indent_first_level = 0;*/ // 1 or 0 - whether to indent the first level of a list   
                        $order_id = $order->getIncrementId();
                        $AWB_No = $result->GenerateWayBillResult->AWBNo;
                        $des_area = $result->GenerateWayBillResult->DestinationArea;
                        $des_loc = $result->GenerateWayBillResult->DestinationLocation;
                        $order_date = date('M d, Y, h:i:s A', strtotime($order->getCreatedAt()));
                        $address = '';

                        $cust_street = $shipping_address->getStreet();
                        $cust_street = $cust_street[0];

                        $cust_resion = $shipping_address->getRegion();
                        $cust_pin = $shipping_address->getPostcode();
                        $cust_phone = $shipping_address->getTelephone();
                        $line_store_address = explode("\n", $blueAddress);
                        foreach ($line_store_address as $add) {
                            $address .= '<p>' . $add . '</p>';
                            $oneLine_address .= $add;
                        }
                        

                        $html_2 = '';
                        if ($payment_method_code == 'cashondelivery') {
                            $html_2 = '<div class="ttl-amnt">
                                            <h2>AMOUNT TO BE COLLECTED <br> Rs. ' . $collectableAmount . '</h2>
                                    </div>';
                        }

                        $html_3 = '<table width="100%" cellspacing="0" cellpadding="8" >
                                <tr>
                                        <td align="center" valign="middle" style="width: 6%;">Sr.</td>
                                        <td align="center" valign="middle" style="width: 10%;">Item Code</td>
                                        <td align="center" valign="middle" style="width: 30%;">Item Description</td>
                                        <td align="center" valign="middle" style="width: 12%;">Quantity</td>
                                        <td align="center" valign="middle" style="width: 12%;">Value</td>
                                        <td align="center" valign="middle" style="width: 12%;">Total Amount</td>
                                </tr>';
                        $j = 1;
                        $productsinawb = [];
                        foreach ($ordered_items as $item) {
                            if (!$item->getParentItemId()) {
                                $sku = $item->getSku();
                                $p_name = $item->getName();
                                $p_qty = number_format($item->getQtyOrdered(), 2);
                                $p_baseprice = number_format($item->getBasePrice(), 2);
                                $p_price = number_format($item->getPrice(), 2);
                                $final_price = number_format((($item->getQtyOrdered()) * ($item->getPrice())), 2);
                                $productsinawb[] = $p_name;
                                $html_3 .= '<tr>
                                            <td align="center" valign="middle" style="width: 6%;">' . $j . '</td>
                                            <td align="center" valign="middle" style="width: 10%;">' . $sku . '</td>
                                            <td align="center" valign="middle" style="width: 30%;">' . $p_name . '</td>
                                            <td align="center" valign="middle" style="width: 12%;">' . $p_qty . '</td>
                                            <td align="center" valign="middle" style="width: 12%;">' . $p_price . '</td>
                                            <td align="center" valign="middle" style="width: 12%;">' . $final_price . '</td>
                                        </tr>';
                                $j++;
                            }
                        }
                        $grand_total = number_format($order->getGrandTotal(), 2);
                        $ship_changes = number_format($order->getShippingAmount(), 2);
                        $tax_amt = number_format($order->getTaxAmount(), 2);


                        $discount_amt = number_format($order->getDiscountAmount(), 2);
                        $dis_html = '';
                        if ($discount_amt) {
                            $dis_html = '<tr>
                                        <td colspan="3" align="center" valign="middle" style="width: 46%;"></td>
                                        <td colspan="2" align="center" valign="middle" style="width: 24%;">' . __('Discount (%s)', $order->getDiscountDescription()) . '</td>
                                        <td align="center" valign="middle" style="width: 12%;">' . $discount_amt . '</td>
                                    </tr>';
                        }

                        $html_3 .= '<tr>
                                    <td colspan="3" align="center" valign="middle" style="width: 46%;"></td>
                                    <td colspan="2" align="center" valign="middle" style="width: 24%;">Shipping Charges</td>
                                    <td align="center" valign="middle" style="width: 12%;">' . $ship_changes . '</td>
                                </tr>
                                <tr>
                                        <td colspan="3" align="center" valign="middle" style="width: 46%;"></td>
                                        <td colspan="2" align="center" valign="middle" style="width: 24%;">Tax Charges</td>
                                        <td align="center" valign="middle" style="width: 12%;">' . $tax_amt . '</td>
                                </tr>	
                                ' . $dis_html . '
                                <tr>
                                        <td colspan="3" align="center" valign="middle" style="width: 46%;"></td>
                                        <td colspan="2" align="center" valign="middle" style="width: 24%;">Total</td>
                                        <td align="center" valign="middle" style="width: 12%;">' . $grand_total . '</td>
                                </tr>	
                        </table>';

                        $invIncrementIDs = '';
                        $invDate = '';
                        if ($order->hasInvoices()) {
                            $invIncrementIDs = array();
                            foreach ($order->getInvoiceCollection() as $inv) {
                                $invIncrementIDs = $inv->getIncrementId();
                                $invDate = date('d-M-Y', strtotime($inv->getCreatedAt()));
                            }
                        }
                        //$order_date = $order->getCreatedAt();
                        $logoUrl = $helper->getMediaUrl() . 'bluedart/' . $helper->getStoreConfig('Softprodigy_Bluedart/general/pdf_logo');
                        //echo $logoUrl;
                        $html = '<html>
                    <head>
                    <meta charset="utf-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <title></title>
                    <meta name="description" content="">

                    </head>
                    <body>
                        <div class="main-block">
                            <div class="sectn-top">
                                <div class="log-main">
                                    <img alt="logo" src="' . $logoUrl . '" />
                                </div>
                                <div class="ship-adrs">
                                    <h2>' . $storeName . '</h2>
                                    ' . $address . '
                                    <p>PIN : ' . $storePincode . '</p>
                                    <p>Phone : ' . $storePhone . '</p>
                                    <p>Email : ' . $email . '</p>
                                    <h4>TIN : ' . $tin . '</h4>
                                </div>
                                <div class="inv-dtails">
                                    <p>INVOICE NO  <span>: ' . $invIncrementIDs . '</span></p>
                                    <p>INVOICE DATE <span>: ' . $invDate . '</span></p>
                                    <p>VAT REG NO <span>: </span></p>
                                    <p>CST REG NO <span>: </span></p>
                                </div>
                            </div>

                            <div class="sectn-mid">
                                <div class="ship-adrs border-no">
                                    <h2>DELIVER TO</h2>
                                    <p>' . $cust_name . '<br>' . $cust_street . '</p>
                                    <p>' . $cust_resion . '</p>
                                    <h2>' . $cust_pin . ' - ' . $des_area . '/' . $des_loc . '</h2>
                                    <p>Phone ' . $cust_phone . '</p>

                                </div>
                                <div class="ordr-dtails">
                                    <div class="o-id">
                                        <h2>ORDER ID</h2>
                                        <div class="img-cntr"><barcode code=' . $order_id . ' type="C39" size="1.0" height="2.0" /></div>
                                        <p>' . $order_id . '</p>
                                    </div>
                                    <div class="o-id o-cod">
                                        <h2>' . $pdf_method . '</h2>
                                        <div class="img-cntr"><barcode code=' . $AWB_No . ' type="C39" size="1.0" height="2.0" /></div>
                                        <p style="width:100%; text-align:center;">' . $AWB_No . '</p>
                                    </div>

                                    <div class="p-details">
                                        <p>AWB No. <span>: ' . $AWB_No . '</span></p>
                                        <p>Weight (kgs) <span>: ' . $actualWeight . '</span></p>
                                        <p>Dimensions (cms) <span>: ' . $dimension . '</span></p>
                                        <p>Order ID <span>: ' . $order_id . '</span></p>
                                        <p>Order Date <span>: ' . $order_date . '</span></p>
                                        <p>Pieces <span>: ' . $qty . '</span></p> 
                                    </div>
                                </div>

                                ' . $html_2 . '

                            </div>

                            <div class="tble-btm">
                                    ' . $html_3 . '
                            </div>
                            <p style="width: 100%; text-align:center; font-size: 16px; margin-bottom: 5px;">This is computer generated document, hence does not require signature.</p>


                             <table class="botm-adrss" cellspacing="0" cellpadding="5" border="0">
                             <tr>
                                     <td style="width: 150px" valign="top"><p style="font-size: 18px; font-weight:600; margin:0">Return Address :</p></td>
                                            <td style="width: 800px" valign="top"><p style="font-size: 16px; font-weight:normal; margin:0 0 0 15px">' . $storeName . ', ' . $oneLine_address . ',' . $storePincode . '</p></td>
                                    </tr>
                            </table>
                        </div>
                    </body>
                    </html>'; //Assign HTML HERE    
                        
                        

                        $awblist = $this->__helper->getAwbModel();
                        $awblist->setData([
                            'awb_number' => $AWB_No,
                            'order_id' => $order->getId(),
                            'order_increment_id' => $order_id,
                            'city_state' => $des_area . '/' . $des_loc,
                            'product_details' => implode(", ", $productsinawb),
                            'awb_weight' => $actualWeight,
                            'awb_date' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s'),
                            'awb_other_info' => serialize([
                                'dimensions' => $dimension,
                                    ]
                            )
                        ]);
                        $awblist->save();
                        
                        require('Softprodigy/mpdf_vendor/autoload.php');
                        $cssPath = $helper->getDirPath('lib_web') . 'sp_bluedart/css/pdf.css'; //any CSS File you want to Include

                        $mpdf = new \mPDF('c', 'B3', '', '', 32, 25, 27, 25, 16, 13);

                        $mpdf->SetDisplayMode('fullpage');

                        $stylesheet = file_get_contents($cssPath);

                        $mpdf->WriteHTML($stylesheet, 1);    // The parameter 1 tells that this is css/style only and no body/html/text

                        $mpdf->WriteHTML($html, 2);

                        if (!is_dir($helper->getDirPath('media') . 'bluredart_pdf/')) {
                            mkdir($helper->getDirPath('media') . 'bluredart_pdf/', 0777);
                        }
                        $file_name = 'order_' . $order_id . '.pdf';
                        $filename = $helper->getDirPath('media') . "bluredart_pdf/" . $file_name;
                        $mpdf->Output($filename, 'F');


                        /*                         * ******End Genarating PDF******** */
                        
                        /*                         * ******Sending PDF To customer******** */

                        $filename = $file_name;
                        $from_mail = "$email";
                        $from_name = "$storeName";
                        $_msg = '';

                        if (isset($param['shiporder']) and $param['shiporder'] == 'yes' and $this->_authorization->isAllowed('Magento_Sales::ship')) {
                            $this->createShipment($order, $AWB_No, $orItems);
                        } else if (isset($param['shiporder']) and $param['shiporder'] == 'yes' and ! $this->_authorization->isAllowed('Magento_Sales::ship')) {
                            $_msg .= 'You are not authorized to create shipment. ';
                        }

                        $this->mail_attachment($filename, $from_mail, $from_name, $order);

                        $_msg .= "Consignment has been submitted, AWB no. is : " . $AWB_No;

                        $errors['success'] = $_msg;
                        //echo "Done"; die;
                    }
                } //-----End of extension enable If statement------
                //$this->_redirect('/admin/sales_order/view', array('order_id' => $param['order_id']));
                //$this->_redirectUrl($param['shipment_referer']);
            } catch (\Exception $e) {
                $errors['error'] = $e->getMessage();
                $this->logger->error($e->__toString());
            }

            $errorHtml = '';
            foreach ($errors as $msgClass => $errmsg) {
                $errorHtml .= $order->getIncrementId() . ': ' . '<div id="messages">
                            <div class="messages">
                                <div class="message message-' . $msgClass . ' ' . $msgClass . '">
                                        <div data-ui-id="messages-message-' . $msgClass . '">
                                        ' . $errmsg . '
                                        </div>
                                </div>
                            </div>
                        </div>';
            }
            return $errorHtml;
        }
        return false;
    }

    private function createShipment($order, $awbno, $orItems) {
        $helper = $this->__helper;
        $shipComment = $helper->getStoreConfig('Softprodigy_Bluedart/order_conf/shipmentcomment');
        $shipComment = $shipComment . "\r\n" . __('Click on this link to track you order: %1', $shipComment);
        $data = [
            'tracking' => [1 => [
                    'carrier_code' => 'custom',
                    'title' => 'Bluedart',
                    'number' => $awbno
                ]],
            'shipment' => [
                'comment_text' => $shipComment,
                'comment_customer_notify' => '1',
                'send_email' => '1',
                'items' => $orItems['items']
            ]
        ];
        try {
            $this->shipmentLoader->setOrderId($order->getId());

            $this->shipmentLoader->setShipment($data['shipment']);
            $this->shipmentLoader->setTracking($data['tracking']);
            $shipment = $this->shipmentLoader->load();
            if (!$shipment) {
                return false;
            }


            $shipment->addComment(
                    $data['shipment']['comment_text'], isset($data['shipment']['comment_customer_notify']), isset($data['shipment']['is_visible_on_front'])
            );

            $shipment->setCustomerNote($data['shipment']['comment_text']);
            $shipment->setCustomerNoteNotify(isset($data['shipment']['comment_customer_notify']));


            $shipment->register();

            $shipment->getOrder()->setCustomerNoteNotify(!empty($data['shipment']['send_email']));

            $this->_saveShipment($shipment);

            if (!empty($data['shipment']['send_email'])) {
                $this->shipmentSender->send($shipment);
            }

            $shipmentCreatedMessage = __('The shipment has been created.');
            $labelCreatedMessage = __('');
            $isNeedCreateLabel = false;
            $this->messageManager->addSuccess(
                    $isNeedCreateLabel ? $shipmentCreatedMessage . ' ' . $labelCreatedMessage : $shipmentCreatedMessage
            );
            return true;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
            return false;
        } catch (\Exception $e) {
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $this->messageManager->addError(__('Cannot save shipment.'));
            return false;
        }
    }

    private function mail_attachment($filename, $from_mail, $from_name, $order) {
        try {
            $helper = $this->__helper;

            /* ------Sending winner email------ */
            $salesName = $helper->getStoreConfig('trans_email/ident_sales/name');
            $mailTosalesEmail = $helper->getStoreConfig('trans_email/ident_sales/email');

            $this->transportBuilder->addTo(
                    $mailTosalesEmail, $salesName
            );

            $emailTemplateVariables = [];
            $emailTemplateVariables['name'] = $salesName;
            $emailTemplateVariables['order_id'] = $order;
            $this->transportBuilder->setTemplateVars($emailTemplateVariables);

            $this->transportBuilder->setTemplateIdentifier('bluedart_shipment_email_template');
            $this->transportBuilder->setTemplateOptions([
                'area' => \Magento\Framework\App\Area::AREA_ADMINHTML,
                'store' => $order->getStoreId()
            ]);

            $this->transportBuilder->getMail()->clearFrom();

            $this->transportBuilder->setFrom(['email' => $from_mail, 'name' => $from_name]);
            //Create an array of variables to assign to template

            $fileContents = file_get_contents($helper->getDirPath('media') . "bluredart_pdf/" . $filename);

            $this->transportBuilder->getMail()->createAttachment(
                    $fileContents, \Zend_Mime::TYPE_OCTETSTREAM, \Zend_Mime::DISPOSITION_ATTACHMENT, \Zend_Mime::ENCODING_BASE64, basename($filename)
            );
            try {
                $transport = $this->transportBuilder->getTransport();
                $transport->sendMessage();
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
            //echo 'doneeee';
        } catch (\Exception $e) {

            $this->logger->error($e->getMessage());
        }
        return true;
    }

}
