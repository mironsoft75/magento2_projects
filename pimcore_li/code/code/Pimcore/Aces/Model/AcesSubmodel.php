<?php
/**
 * Created by pimcore.
 * Date: 15/9/18
 * Time: 4:31 PM
 */

namespace Pimcore\Aces\Model;


use Magento\Framework\Model\AbstractModel;


class AcesSubmodel extends AbstractModel
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Pimcore\Aces\Model\ResourceModel\AcesSubmodel::class);
    }
}