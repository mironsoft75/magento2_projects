<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/**
 * Payflow link infoblock
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */

namespace Magento\Ccavenuepay\Block\Ccavenuepay;

class Info extends \Magento\Ccavenuepay\Block\Info\Ccavenuepay {

    /**
     * Don't show CC type
     *
     * @return false
     */
    public function getCcavenuepayTypeName() {
        return false;
    }

}
