<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Model\ResourceModel;

class HomepageBlocks extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * HomepageBlocks constructor
     */
    protected function _construct()
    {
        $this->_init('codilar_offers','id');
    }
}
