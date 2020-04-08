<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/5/19
 * Time: 4:42 PM
 */

namespace Codilar\MasterTables\Model\Import\LocationName;

/**
 * Interface RowValidatorInterface
 * @package Codilar\MasterTables\Model\Import\LocationName
 */
interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{


    const ERROR_LOCATION_NAME_IS_EMPTY = 'Location Name is empty';
    const ERROR_DEPT_IS_EMPTY = 'Dept is empty';
    const ERROR_SHOW_AS_INSTOCK_IS_EMPTY = 'Instock field is empty';

    /**
     * Initialize validator
     *
     * @param $context
     * @return $this
     */
    public function init($context);
}