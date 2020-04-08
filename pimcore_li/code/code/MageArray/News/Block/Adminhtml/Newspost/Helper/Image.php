<?php

namespace MageArray\News\Block\Adminhtml\Newspost\Helper;

use MageArray\News\Helper\Data as NewspostImage;
use Magento\Framework\Data\Form\Element\CollectionFactory as ElementCollectionFactory;
use Magento\Framework\Data\Form\Element\Factory as ElementFactory;
use Magento\Framework\Data\Form\Element\Image as ImageField;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;

/**
 * Class Image
 * @package MageArray\News\Block\Adminhtml\Newspost\Helper
 */
class Image extends ImageField
{
    /**
     * @var NewspostImage
     */
    protected $imageModel;

    /**
     * Image constructor.
     * @param NewspostImage $imageModel
     * @param ElementFactory $factoryElement
     * @param ElementCollectionFactory $factoryCollection
     * @param Escaper $escaper
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        NewspostImage $imageModel,
        ElementFactory $factoryElement,
        ElementCollectionFactory $factoryCollection,
        Escaper $escaper,
        UrlInterface $urlBuilder,
        $data = []
    ) {
        $this->imageModel = $imageModel;
        parent::__construct(
            $factoryElement,
            $factoryCollection,
            $escaper,
            $urlBuilder,
            $data
        );
    }

    /**
     * @return bool|string
     */
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = $this->imageModel->getBaseUrlMedia() . $this->getValue();
        }
        return $url;
    }
}
