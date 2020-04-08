<?php
/**
 *
 * @package     codilar
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        https://www.codilar.com/
 */

namespace Codilar\AdminLogs\Model\Source;


class FullActionName extends AbstractTable
{

    protected function getTableData()
    {
        return [
            'full_action_name'  =>  [
                'label' =>  __("Full Action Name")
            ]
        ];
    }
}