<?php

namespace Pimcore\Aces\Ui\Component\Submodel\Actions;

/**
 * Class PageAction
 * @package Pimcore\Aces\Ui\Component\Submodel\Actions
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
                if (isset($item["id"])) {
                    $id = $item["id"];
                }
                $item[$name]["view"] = [
                    "href" => $this->getContext()->getUrl(
                        "aces/submodel/edit", ["id" => $id]),
                    "label" => __("Edit")
                ];
            }
        }
        return $dataSource;
    }

}
