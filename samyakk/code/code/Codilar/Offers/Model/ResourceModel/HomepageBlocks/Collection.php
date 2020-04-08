<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Model\ResourceModel\HomepageBlocks;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = "id";

    /**
     * Collection constructor
     */
    protected function _construct()
    {
        $this->_init('Codilar\Offers\Model\HomepageBlocks','Codilar\Offers\Model\ResourceModel\HomepageBlocks');
    }
}
