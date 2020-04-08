<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 19/7/19
 * Time: 5:15 PM
 */

namespace Codilar\SearchApi\Model\Data\SearchSuggestion;

use Codilar\SearchApi\Api\Data\SearchSuggestion\SearchDataInterface;

class SearchData extends \Magento\Framework\DataObject implements SearchDataInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return $this->getData('name');
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setData('name', $name);
    }
}
