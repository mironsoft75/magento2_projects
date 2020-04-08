<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 4:40 PM
 */

namespace Codilar\MasterTables\Ui\Component\StoneBom\Action;

/**
 * Class PageAction
 * @package Codilar\StoneAndMetalRates\Ui\Component\Metal\Action
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
                if (isset($item["stone_bom_id"])) {
                    $id = $item["stone_bom_id"];
                }
                $item[$name]["view"] = [
                    "href" => $this->getContext()->getUrl(
                        "mastertables/stonebom/edit", ["stone_bom_id" => $id]),
                    "label" => __("Edit")
                ];
            }
        }
        return $dataSource;
    }

}