<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 22/11/18
 * Time: 5:12 PM
 */

namespace Codilar\Videostore\Model\ResourceModel\VideostoreRequest;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'videostore_request_id';
    protected $_eventPrefix = 'codilar_videostore_request_collection';
    protected $_eventObject = 'videostore_request_collection';

    /**
     * Collection constructor.
     */
    public function _construct()
    {
         $this->_init('Codilar\Videostore\Model\VideostoreRequest', 'Codilar\Videostore\Model\ResourceModel\VideostoreRequest');
    }
}