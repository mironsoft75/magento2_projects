<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */


namespace Codilar\Cms\Api\Data;


interface BlockInterface
{
    const SHOW_IN_HOMEPAGE = "show_in_homepage";
    const SORT_ORDER = "sort_order";
    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return \Codilar\Cms\Api\Data\BlockInterface
     */
    public function setTitle($title);

    /**
     * @return int
     */
    public function getSortOrder();

    /**
     * @param int $sortOrder
     * @return \Codilar\Cms\Api\Data\BlockInterface
     */
    public function setSortOrder($sortOrder);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     * @return \Codilar\Cms\Api\Data\BlockInterface
     */
    public function setContent($content);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \Codilar\Cms\Api\Data\BlockInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getDesignIdentifier();

    /**
     * @param string $designIdentifier
     * @return $this
     */
    public function setDesignIdentifier($designIdentifier);
}
