<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\Comments\Grid\Renderer;
use Magento\Framework\DataObject;

class Stores extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    private $store;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\System\Store $store,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->store = $store;
    }

    public function render(DataObject $row)
    {
        $stores = $row->getData('store_id');
        if (!$stores) {
            return __('Restricts in All');
        }

        $html = '';
        $data = $this->store->getStoresStructure(false, explode(',', $stores));
        foreach ($data as $website) {
            $html .= $website['label'] . '<br/>';
            foreach ($website['children'] as $group) {
                $html .= str_repeat('&nbsp;', 3) . $group['label'] . '<br/>';
                foreach ($group['children'] as $store) {
                    $html .= str_repeat('&nbsp;', 6) . $store['label'] . '<br/>';
                }
            }
        }

        if (!$html) {
            $html = __('All Store Views');
        }

        return $html;
    }

}