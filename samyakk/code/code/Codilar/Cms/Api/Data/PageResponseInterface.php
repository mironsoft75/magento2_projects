<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/4/19
 * Time: 12:52 PM
 */

namespace Codilar\Cms\Api\Data;


interface PageResponseInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return boolean
     */
    public function getStatus();

    /**
     * @param boolean $status
     * @return \Codilar\Cms\Api\Data\PageResponseInterface
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return \Codilar\Cms\Api\Data\PageResponseInterface
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getMetaDescription();

    /**
     * @param string $metaDescription
     * @return \Codilar\Cms\Api\Data\PageResponseInterface
     */
    public function setMetaDescription($metaDescription);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     * @return \Codilar\Cms\Api\Data\PageResponseInterface
     */
    public function setContent($content);
}