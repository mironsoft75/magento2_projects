<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 19/7/19
 * Time: 4:13 PM
 */

namespace Codilar\SearchApi\Model\Config;

class Sort
{
    public function toOptionArray()
    {
        return [['value' => 'ASC', 'label' => 'Ascending'], ['value' => 'DESC', 'label' => 'Descending']];
    }
}
