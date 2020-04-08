<?php

namespace Codilar\Rapnet\Ui\Component\Actions;

/**
 * Class PageAction
 * @package Codilar\Rapnet\Ui\Component\Actions
 */
class PageAction extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as & $item) {
                $name = $this->getData("name");
                $id = "X";
                if (isset($item["url_rewrite_id"])) {
                    $id = $item["url_rewrite_id"];
                }
                $item[$name]["view"] = [
                    "href" => $this->getContext()->getUrl(
                        "rapnet/urlrewrite/edit",
                        ["url_rewrite_id" => $id]
                    ),
                    "label" => __("Edit")
                ];
                $item[$name]['delete'] = [
                    "href" => $this->getContext()->getUrl(
                        "rapnet/urlrewrite/delete",
                        ["url_rewrite_id" => $id]
                    ),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete UrlRewrite'),
                        'message' => __('Are you sure you wan\'t to delete the UrlRewrite?')
                    ]
                ];
            }
        }
        return $dataSource;
    }
}
