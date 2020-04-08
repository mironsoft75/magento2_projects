<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/4/19
 * Time: 12:55 PM
 */

namespace Codilar\Cms\Model;

use Codilar\Cms\Api\Data\PageResponseInterface;
use Magento\Framework\DataObject;

class Page extends DataObject implements PageResponseInterface
{

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getData('id');
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData('id', $id);
    }

    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->getData("status");
    }

    /**
     * @param boolean $status
     * @return \Codilar\Cms\Api\Data\PageResponseInterface
     */
    public function setStatus($status)
    {
        return $this->setData("status", $status);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->getData("content");
    }

    /**
     * @param string $content
     * @return \Codilar\Cms\Api\Data\PageResponseInterface
     */
    public function setContent($content)
    {
        return $this->setData("content", $content);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getData("title");
    }

    /**
     * @param string $title
     * @return \Codilar\Cms\Api\Data\PageResponseInterface
     */
    public function setTitle($title)
    {
        return $this->setData("title", $title);
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->getData("meta_description");
    }

    /**
     * @param string $metaDescription
     * @return \Codilar\Cms\Api\Data\PageResponseInterface
     */
    public function setMetaDescription($metaDescription)
    {
        return $this->setData("meta_description", $metaDescription);
    }
}