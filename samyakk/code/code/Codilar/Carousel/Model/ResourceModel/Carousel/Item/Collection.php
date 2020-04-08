<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Model\ResourceModel\Carousel\Item;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Codilar\Carousel\Model\Carousel\Item as Model;
use Codilar\Carousel\Model\ResourceModel\Carousel\Item as ResourceModel;

class Collection extends AbstractCollection
{

    protected $_idFieldName = ResourceModel::ID_FIELD_NAME;

    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}