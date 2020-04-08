<?php
/**
 * Created by pimcore.
 * Date: 15/9/18
 * Time: 4:34 PM
 */

namespace Pimcore\Aces\Model\ResourceModel;


class AcesProducts extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('pimcore_aces_products', "id");
    }
}