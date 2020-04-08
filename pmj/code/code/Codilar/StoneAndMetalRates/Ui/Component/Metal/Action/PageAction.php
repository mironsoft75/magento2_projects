<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 11/11/18
 * Time: 11:59 AM
 */
namespace Codilar\StoneAndMetalRates\Ui\Component\Metal\Action;

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
                $id = "X";
                if (isset($item["entity_id"])) {
                    $id = $item["entity_id"];
                }
                $item[$name]["view"] = [
                    "href" => $this->getContext()->getUrl(
                        "rate/metal/edit", ["entity_id" => $id]),
                    "label" => __("Edit")
                ];
            }
        }
        return $dataSource;
    }

}
