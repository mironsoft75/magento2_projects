<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Ccavenuepay\Model\Ccavenuepay\Response;

use Magento\Ccavenuepay\Model\Response\Factory as CcavenuepayResponseFactory;

/**
 * Factory class for @see \Magento\Ccavenuepay\Model\Ccavenuepay\Response
 */
class Factory extends CcavenuepayResponseFactory {

    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(
    \Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = 'Magento\Ccavenuepay\Model\Ccavenuepay\Response'
    ) {
        parent::__construct($objectManager, $instanceName);
    }

}
