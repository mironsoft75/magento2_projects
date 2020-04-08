<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/5/19
 * Time: 4:42 PM
 */

namespace Codilar\MasterTables\Model\Import\StoneBom;

/**
 * Interface RowValidatorInterface
 * @package Codilar\MasterTables\Model\Import\LocationName
 */
interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{


    const ERROR_STONE_BOM_VARIANT_IS_EMPTY = 'Stone Bom Variant  is empty';
    const ERROR_STONE_TYPE_IS_EMPTY = 'Stone Type is empty';
    const ERROR_INDIAN_RATE_CARAT_IS_EMPTY = 'Indian Rate is empty';
    const ERROR_USA_RATE_CARAT_IS_EMPTY = 'USA Rate is empty';

    /**
     * Initialize validator
     *
     * @param $context
     * @return $this
     */
    public function init($context);
}