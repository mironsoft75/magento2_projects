<?php

namespace Magento\Ccavenuepay\Controller\Ccavenuepay;

class SilentPost extends \Magento\Ccavenuepay\Controller\Ccavenuepay {

    /**
     * Get response from ccavenuepay by silent post method
     *
     * @return void
     */
    public function execute() {
        $quoteId = $this->_getCheckout()->getLastRealOrder()->getQuoteId();
        $this->_getCcavenuepayPostSession()->setQuoteId($quoteId);
        $data = $this->getRequest()->getPostValue();
        $ccavenuepay = $this->_objectManager->get('Magento\Ccavenuepay\Model\Ccavenuepay');
        $order = $this->_getCheckout()->getLastRealOrder();
        $order->setStatus($ccavenuepay->getConfigData('new_order_status'));
        $order->save();
        $encrypted_data = $ccavenuepay->getEncryptedData($order);
        $this->logger->info("===SlientPost encryption data===" . $encrypted_data);
        $ccavenueTransactionUrl = $ccavenuepay->getCcavenueTransactionUrl();
        $this->logger->info("===SlientPost Transaction Url===" . $ccavenueTransactionUrl);
        $access_code = $ccavenuepay->getConfigData('access_code');
        $this->logger->info("===SlientPost Access Code===" . $access_code);
        $layout = $this->_view->getLayout();
        $block = $layout->createBlock('Magento\Ccavenuepay\Block\Ccavenuepay\Form');
        $gif = $block->getViewFileUrl('Magento_Ccavenuepay::image/ajax-loader.gif');

	echo '<center>You will be redirected to CCAvenue in a few seconds.<br><img src="https://www.ccavenue.com/images_shoppingcart/ccavenue_logo_india.png" alt="Logo" width="162px" height="37px"><br><br><img src="' . $gif . '" alt="ajax-loader" align="center" width="128px" height="15px"><br><b>Copyright © 2001 - '.date("Y").' Infibeam Avenues Ltd. All Rights Reserved.</b></center><form method="post" name="redirect" action="' . $ccavenueTransactionUrl . '"> 
                <input type=hidden name=encRequest value="' . $encrypted_data . '">
                <input type=hidden name=access_code value="' . $access_code . '">
                </form>
                </center>
                <script type="text/javascript">
                function formsubmit()
                {
                  document.redirect.submit();   
                }
                setTimeout("formsubmit()", 3000);
                </script>';
        exit(0);
    }

}
