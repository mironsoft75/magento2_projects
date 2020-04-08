<?php
/** 
 * BSS Commerce Co. 
 * 
 * NOTICE OF LICENSE 
 * 
 * This source file is subject to the EULA 
 * that is bundled with this package in the file LICENSE.txt. 
 * It is also available through the world-wide-web at this URL: 
 * http://bsscommerce.com/Bss-Commerce-License.txt 
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE 
 * ================================================================= 
 * This package designed for Magento COMMUNITY edition 
 * BSS Commerce does not guarantee correct work of this extension 
 * on any other Magento edition except Magento COMMUNITY edition. 
 * BSS Commerce does not provide extension support in case of 
 * incorrect edition usage. 
 * ================================================================= 
 * 
 * @category   BSS 
 * @package    Bss_Gallery 
 * @author     Extension Team 
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com ) 
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt 
 */ 
namespace Bss\Gallery\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class CategoryActions extends Column
{
    /** Url path */
    const CATEGORY_URL_PATH_EDIT = 'gallery/category/edit';
    // const CATEGORY_URL_PATH_DELETE = 'gallery/category/delete';
    // const CATEGORY_URL_PATH_LIST = 'gallery/item/index';

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;
    private $listUrl;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::CATEGORY_URL_PATH_EDIT
        // $listUrl = self::CATEGORY_URL_PATH_LIST
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        // $this->listUrl = $listUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['category_id'])) {
                    // $item[$name]['list'] = [
                    //     'href' => $this->urlBuilder->getUrl($this->listUrl, ['category_id' => $item['category_id']]),
                    //     'label' => __('View List Item')
                    // ];
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['category_id' => $item['category_id']]),
                        'label' => __('Edit')
                    ];
                    // $item[$name]['delete'] = [
                    //     'href' => $this->urlBuilder->getUrl(self::CATEGORY_URL_PATH_DELETE, ['category_id' => $item['category_id']]),
                    //     'label' => __('Delete'),
                    //     'confirm' => [
                    //         'title' => __('Delete "${ $.$data.title }"'),
                    //         'message' => __('Are you sure you wan\'t to delete a "${ $.$data.title }" record?')
                    //     ]
                    // ];
                }
            }
        }

        return $dataSource;
    }
}
