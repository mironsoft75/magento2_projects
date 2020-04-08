<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 22/11/18
 * Time: 3:16 PM
 */

namespace Codilar\Videostore\Model\ResourceModel\VideostoreCart;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'videostore_cart_id';
	protected $_eventPrefix = 'codilar_videostore_cart_collection';
	protected $_eventObject = 'videostore_cart_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		 $this->_init('Codilar\Videostore\Model\VideostoreCart', 'Codilar\Videostore\Model\ResourceModel\VideostoreCart');
	}

}