<?php
/**
 * @package     magento 2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Model\Carousel\Item;

use Codilar\Carousel\Api\Data\CarouselItemLinkInterface;
use Magento\Framework\DataObject;

class Link extends DataObject implements CarouselItemLinkInterface
{

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getData("type");
    }

    /**
     * @param string $type
     * @return \Codilar\Carousel\Api\Data\CarouselItemLinkInterface
     */
    public function setType($type)
    {
        return $this->setData("type", $type);
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getData("identifier");
    }

    /**
     * @param string $identifier
     * @return \Codilar\Carousel\Api\Data\CarouselItemLinkInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData("identifier", $identifier);
    }
}