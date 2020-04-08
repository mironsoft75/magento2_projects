<?php

namespace Magento\Ccavenuepay\Model\Adminhtml\Source;

use Magento\Payment\Model\Method\AbstractMethod;

/**
 * Class PaymentAction
 */
class TransactionMode implements \Magento\Framework\Option\ArrayInterface {

    /**
     * {@inheritdoc}
     */
    public function toOptionArray() {
        return [
            [
                'value' => "TEST",
                'label' => __('Test')
            ],
            [
                'value' => "LIVE",
                'label' => __('Live')
            ]
        ];
    }

}

