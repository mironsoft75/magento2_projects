<?php

namespace Pimcore\Aces\Ui\Component\Ymm\Actions;

/**
 * Class PageAction
 * @package Pimcore\Aces\Ui\Component\Ymm\Actions
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
                if (isset($item["base_vehicle_id"])) {
                    $id = $item["base_vehicle_id"];
                }
                $item[$name]["view"] = [
                    "href" => $this->getContext()->getUrl(
                        "aces/ymm/edit", ["base_vehicle_id" => $id]),
                    "label" => __("Edit")
                ];
            }
        }
        return $dataSource;
    }

}
