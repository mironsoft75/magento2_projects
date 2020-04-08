<?php
namespace Softprodigy\Bluedart\Controller\Adminhtml;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Softprodigy\Bluedart\Model\Mail\TransportBuilder;
use Magento\Sales\Model\Order\Email\Sender\ShipmentSender;
/**
 * Description of AbstractAction
 *
 * @author mannu
 */
abstract class AbstractAction extends \Magento\Backend\App\Action {
    const XML_PATH_EMAIL_RECIPIENT = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER = 'contacts/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE = 'contacts/email/email_template';
    const XML_PATH_EMAIL_PDF_SENDER = 'sales_email/invoice/pdf_sender';
}
