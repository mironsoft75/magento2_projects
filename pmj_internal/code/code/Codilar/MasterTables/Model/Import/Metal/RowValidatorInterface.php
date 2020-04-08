<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/5/19
 * Time: 4:42 PM
 */

namespace Codilar\MasterTables\Model\Import\Metal;

/**
 * Interface RowValidatorInterface
 * @package Codilar\MasterTables\Model\Import\LocationName
 */
interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{


    const ERROR_KARAT_COLOR_IS_EMPTY = 'Karat Color is empty';
    const ERROR_METAL_TYPE_IS_EMPTY = 'Metal Type is empty';
    const ERROR_KARAT_IS_EMPTY = 'Karat field is empty';
    const ERROR_METAL_COLOR_IS_EMPTY = 'Metal Color is empty';


    /**
     * Initialize validator
     *
     * @param $context
     * @return $this
     */
    public function init($context);
}