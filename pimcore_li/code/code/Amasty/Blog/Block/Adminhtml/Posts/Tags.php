<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\Posts;

class Tags extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Amasty\Blog\Model\ResourceModel\Tags\Collection
     */
    private $tagsCollection;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Amasty\Blog\Model\ResourceModel\Tags\Collection $tagsCollection,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->tagsCollection = $tagsCollection;
    }

    public function getTagsNamesArray()
    {
        $tagsList = $this->tagsCollection->load();
        $nameArray = [];
        foreach ($tagsList as $tag) {
            $nameArray[] = $tag->getName();
        }
        return $nameArray;
    }


}
