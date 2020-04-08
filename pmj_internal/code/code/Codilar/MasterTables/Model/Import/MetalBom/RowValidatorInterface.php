<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/5/19
 * Time: 4:42 PM
 */

namespace Codilar\MasterTables\Model\Import\MetalBom;

/**
 * Interface RowValidatorInterface
 * @package Codilar\MasterTables\Model\Import\LocationName
 */
interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{


    const ERROR_METAL_BOM_VARIANT_IS_EMPTY = 'Variant Name is empty';
    const ERROR_ITEM_CODE_IS_EMPTY = 'Display field is empty';
    const ERROR_INDIAN_RATE_GRAM_IS_EMPTY = 'Indian Rate is empty';
    const ERROR_USA_RATE_GRAM_IS_EMPTY = 'USA Rate is empty';

    /**
     * Initialize validator
     *
     * @param $context
     * @return $this
     */
    public function init($context);
}