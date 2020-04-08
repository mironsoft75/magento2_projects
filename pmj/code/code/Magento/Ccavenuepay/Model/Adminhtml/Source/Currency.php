<?php

namespace Magento\Ccavenuepay\Model\Adminhtml\Source;

use Magento\Payment\Model\Method\AbstractMethod;

/**
 * Class PaymentAction
 */
class Currency implements \Magento\Framework\Option\ArrayInterface {

    /**
     * {@inheritdoc}
     */
    public function toOptionArray() {
        return [
            [
                'value' => "INR",
                'label' => __('Indian Rupee')
            ],
            [
                'value' => "USD",
                'label' => __('United States Dollar')
            ],
            [
                'value' => "SGD",
                'label' => __('Singapore Dollar')
            ],
            [
                'value' => "GBP",
                'label' => __('Pound Sterling')
            ],
            [
                'value' => "EUR",
                'label' => __('Euro')
            ]
        ];
    }

}
