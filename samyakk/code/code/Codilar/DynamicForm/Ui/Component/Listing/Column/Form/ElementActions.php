<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Ui\Component\Listing\Column\Form;


use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;


class ElementActions extends Column
{
    const URL_PATH_EDIT = "dynamicform/element/edit";
    const URL_PATH_DELETE = "dynamicform/element/delete";

    /**
     * @var UrlInterface
     */
    private $url;
    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * FormActions constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $url
     * @param Escaper $escaper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $url,
        Escaper $escaper,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->url = $url;
        $this->escaper = $escaper;
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['id'])) {
                    $title = $this->escaper->escapeHtml($item['identifier']);
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->url->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'id' => $item['id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->url->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'id' => $item['id']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete %1', $title),
                                'message' => __('Are you sure you want to delete a %1 record?', $title)
                            ],
                            'post' => true
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}