<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Ccavenuepay\Model\Ccavenuepay\Request;

use Magento\Ccavenuepay\Model\Request\Factory as CcavenuepayRequestFactory;

/**
 * Factory class for @see \Magento\Ccavenuepay\Model\Ccavenuepay\Request
 */
class Factory extends CcavenuepayRequestFactory {

    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(
    \Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = 'Magento\Ccavenuepay\Model\Ccavenuepay\Request'
    ) {
        parent::__construct($objectManager, $instanceName);
    }

}
