<?php

namespace Codilar\ContactUs\Model;
/**
 * Class ContactUs
 * @package Codilar\ContactUs\Model
 */
class ContactUs extends \Magento\Framework\Model\AbstractModel
{
    const CACHE_TAG = 'contact_us';
    /**
     * @var string
     */
    protected $_cacheTag = 'contact_us';
    /**
     * @var string
     */
    protected $_eventPrefix = 'contact_us';

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    protected function _construct()
    {
        $this->_init('Codilar\ContactUs\Model\ResourceModel\ContactUs');
    }
}
