<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Breadcrumbs\Model\Data\Breadcrumbs;


use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterface;
use Magento\Framework\DataObject;

class BreadcrumbsResponseData extends DataObject implements BreadcrumbsResponseDataInterface
{
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getData('title');
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData('title', $title);
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->getData('link');
    }

    /**
     * @param string $link
     * @return $this
     */
    public function setLink($link)
    {
        return $this->setData('link', $link);
    }
}