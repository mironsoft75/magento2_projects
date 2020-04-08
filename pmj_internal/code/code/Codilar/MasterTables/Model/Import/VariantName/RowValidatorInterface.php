<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/5/19
 * Time: 4:42 PM
 */

namespace Codilar\MasterTables\Model\Import\VariantName;

/**
 * Interface RowValidatorInterface
 * @package Codilar\MasterTables\Model\Import\LocationName
 */
interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{


    const ERROR_VARIANT_NAME_IS_EMPTY = 'Variant Name is empty';
    const ERROR_DISPLAY_IN_UI_IS_EMPTY = 'Display field is empty';

    /**
     * Initialize validator
     *
     * @param $context
     * @return $this
     */
    public function init($context);
}