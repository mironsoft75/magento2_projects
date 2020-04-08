<?php

namespace MageArray\News\Model;

use Magento\Framework\Option\ArrayInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Categories
 * @package MageArray\News\Model
 */
class Categories extends Template implements ArrayInterface
{
    /**
     * @var NewscatFactory
     */
    protected $_newscatFactory;

    /**
     * @var
     */
    protected $_catOption;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Categories constructor.
     * @param Context $context
     * @param NewscatFactory $newscatFactory
     */
    public function __construct(
        Context $context,
        NewscatFactory $newscatFactory
    ) {
        parent::__construct($context);
        $this->_newscatFactory = $newscatFactory;
    }

    /**
     * @param $id
     * @return bool
     */
    public function hasChild($id)
    {
        $newscatModel = $this->_newscatFactory->create();
        $newscatColl = $newscatModel->load($id);
        if ($newscatColl->getId()) {
            return true;
        }
        return false;
    }

    /**
     * @param int $parent
     * @param int $level
     */
    public function dumpTree($parent = 0, $level = 0)
    {
        $newscatModel = $this->_newscatFactory->create();
        $newscatColl = $newscatModel->getCollection()
            ->setActiveFilter(true);
        $nonEscapableNbspChar = html_entity_decode(
            '&#160;',
            ENT_NOQUOTES,
            'UTF-8'
        );
        foreach ($newscatColl as $cat) {
            if ($parent == $cat['cat_parent']) {
                $this->_catOption['options'][$cat['cat_id']] = str_repeat(
                        $nonEscapableNbspChar,
                        $level * 4
                ) . $cat['cat_name'];
                if ($this->hasChild($cat['cat_id'])) {
                    $this->dumpTree($cat['cat_id'], $level + 1);
                }
            }
        }
    }

    /**
     * @param int $parent
     * @param int $level
     */
    public function dumpTreedesign($parent = 0, $level = 0)
    {
        $newscatModel = $this->_newscatFactory->create();
        $newscatColl = $newscatModel->getCollection()
            ->setActiveFilter(true)
            ->setStoreFilter($this->getStoreId());
        $nonEscapableNbspChar = html_entity_decode(
            '&#160;',
            ENT_NOQUOTES,
            'UTF-8'
        );
        foreach ($newscatColl as $cat) {
            if ($parent == $cat['cat_parent']) {
                $this->_catOption['optionsdesign']
                [$cat['cat_url_key']] = $cat['cat_id'] .
                    '*' .
                    str_repeat(
                        $nonEscapableNbspChar,
                        $level * 4
                    ) . $cat['cat_name'];
                if ($this->hasChild($cat['cat_id'])) {
                    $this->dumpTreedesign($cat['cat_id'], $level + 1);
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getOptionArraytwo()
    {
        $this->_catOption['options'][0] = 'Create Parent';
        $this->dumpTree();
        return $this->_catOption['options'];
    }

    /**
     * @return mixed
     */
    public function getOptionArray()
    {
        if (!isset($this->_catOption['options'])) {
            $this->_catOption['options'] = [];
        }
        $this->dumpTree();
        return $this->_catOption['options'];
    }

    /**
     * @return mixed
     */
    public function getfrontOptionArray()
    {
        if (empty($this->_catOption['optionsdesign'])) {
            $this->_catOption['optionsdesign'] = [];
        }
        $this->dumpTreedesign();
        return $this->_catOption['optionsdesign'];
    }

    /**
     * @return array
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['label' => $value, 'value' => $index];
        }

        return $result;
    }

    /**
     * @param $optionId
     * @return null
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
}
