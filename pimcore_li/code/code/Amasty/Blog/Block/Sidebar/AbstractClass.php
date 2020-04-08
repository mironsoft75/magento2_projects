<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Sidebar;

use Amasty\Blog\Helper\Settings;
use Amasty\Blog\Helper\Url;

class AbstractClass extends \Amasty\Blog\Block\Layout\AbstractClass
{

    /**
     * @var Settings
     */
    protected $settingsHelper;

    /**
     * @var \Amasty\Blog\Helper\Date
     */
    protected $dateHelper;

    /**
     * @var \Amasty\Blog\Model\Posts
     */
    protected $postsModel;

    /**
     * @var \Amasty\Blog\Model\Categories
     */
    protected $categoryModel;

    /**
     * @var \Amasty\Blog\Model\Comments
     */
    protected $commentsModel;

    /**
     * @var \Amasty\Blog\Model\Tags
     */
    protected $tagsModel;

    /**
     * @var \Amasty\Blog\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Amasty\Blog\Helper\Strings
     */
    protected $stringsHelper;

    /**
     * @var Url
     */
    protected $urlHelper;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * @var \Amasty\Blog\Helper\Resize
     */
    protected $resizeHelper;

    /**
     * AbstractClass constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param Settings                                         $settingsHelper
     * @param \Amasty\Blog\Helper\Date                         $dateHelper
     * @param \Amasty\Blog\Helper\Data                         $dataHelper
     * @param \Amasty\Blog\Model\Posts                         $postsModel
     * @param \Amasty\Blog\Model\Categories                    $categoryModel
     * @param \Amasty\Blog\Model\Comments                      $commentsModel
     * @param \Amasty\Blog\Model\Tags                          $tagsModel
     * @param \Magento\Framework\Registry                      $registry
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Amasty\Blog\Helper\Settings $settingsHelper,
        \Amasty\Blog\Helper\Date $dateHelper,
        \Amasty\Blog\Helper\Data $dataHelper,
        \Amasty\Blog\Helper\Url $urlHelper,
        \Amasty\Blog\Helper\Strings $stringsHelper,
        \Amasty\Blog\Model\Posts $postsModel,
        \Amasty\Blog\Model\Categories $categoryModel,
        \Amasty\Blog\Model\Comments $commentsModel,
        \Amasty\Blog\Model\Tags $tagsModel,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Amasty\Blog\Helper\Resize $resizeHelper,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->settingsHelper = $settingsHelper;
        $this->dateHelper = $dateHelper;
        $this->postsModel = $postsModel;
        $this->categoryModel = $categoryModel;
        $this->commentsModel = $commentsModel;
        $this->tagsModel = $tagsModel;
        $this->dataHelper = $dataHelper;
        $this->registry = $registry;
        $this->stringsHelper = $stringsHelper;
        $this->urlHelper = $urlHelper;
        $this->imageHelper = $imageHelper;
        $this->resizeHelper = $resizeHelper;
    }

    /**
     * Route to get configuration
     *
     * @var string
     */
    protected $_route = 'abstract';

    /**
     * Place to define displaying
     *
     * @var string
     */
    protected $_place;

    protected $_keysToCache = ['place']; # Can be array

    protected function _isRequestMatchParams($moduleName, $controller, $action)
    {
        $request = $this->getRequest();
        return
            $request->getModuleName() == $moduleName
            && $request->getControllerName() == $controller
            && $request->getActionName() == $action;
    }

    protected function _prepareCollectionToStart($collection, $limit)
    {
        $collection
            ->setPageSize($limit)
            ->setCurPage(1);

        return $this;
    }

    protected function _dataHash()
    {
        if ($this->_keysToCache && is_array($this->_keysToCache)) {
            $values = [];
            foreach ($this->_keysToCache as $key) {
                if ($this->getData($key)) {
                    $values[] = $this->getData($key);
                }
            }
            return implode("_", $values);
        }
        return false;
    }

    /**
     * Wrapper for standard strip_tags() function with extra functionality for html entities
     *
     * @param string $data
     * @param string $allowableTags
     * @param bool   $allowHtmlEntities
     *
     * @return string
     */
    public function stripTags($data, $allowableTags = null, $allowHtmlEntities = false)
    {
        return $this->dataHelper->stripTags($data, $allowableTags, $allowHtmlEntities);
    }

    public function setPlace($place)
    {
        $this->_place = $place;
        return $this;
    }

    public function getConfPlace()
    {
        return $this->settingsHelper->getConfPlace($this->getRoute());
    }

    public function getRoute()
    {
        return $this->_route;
    }

    public function getDisplay()
    {
        return is_null($this->_place) || ($this->getConfPlace() && ($this->_place == $this->getConfPlace()));
    }

    protected function _checkCategory($collection)
    {
        return $this;
    }

    public function getHeaderHtml($post = null)
    {
        return $this->dataHelper->getHeaderHtml($post);
    }

    public function getFooterHtml($post = null)
    {
        return $this->dataHelper->getFooterHtml($post);
    }

    public function getColorClass()
    {
        return $this->settingsHelper->getIconColorClass();
    }

    public function getStrippedContent($content)
    {
        $limit = $this->settingsHelper->getRecentPostsShortLimit();

        $content = $this->stringsHelper->htmlToPlainText($content);

        if ($this->stringsHelper->strlen($content) > $limit) {
            $content = $this->stringsHelper->substr($content, 0, $limit);
            if ($this->stringsHelper->strpos($content, " ") !== false) {
                $cuts = explode(" ", $content);
                if (count($cuts) && count($cuts) > 1) {
                    unset($cuts[count($cuts) - 1]);
                    $content = implode(" ", $cuts);
                }
            }

            return $content . "...";
        }

        return $content;
    }
}
