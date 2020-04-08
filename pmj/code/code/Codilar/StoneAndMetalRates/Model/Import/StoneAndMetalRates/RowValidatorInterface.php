<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codilar\StoneAndMetalRates\Model\Import\StoneAndMetalRates;

/**
 * Interface RowValidatorInterface
 * @package Codilar\StoneAndMetalRates\Model\Import\StoneAndMetalRates
 */
interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{

    const ERROR_NAME_IS_EMPTY='Empty Name';
    const ERROR_RATE_IS_EMPTY='Empty Rate';
    const ERROR_TYPE_IS_EMPTY='empty Type';
    /**
     * Initialize validator
     *
     * @param $context
     * @return $this
     */
    public function init($context);
}
