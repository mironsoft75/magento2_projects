<?php


namespace Pimcore\Aces\Api;


interface AcesProductsManagementInterface
{
    /**
     * @param $productSku
     * @param string $selectAttributes
     * @return $this|array
     */
    public function getAcesVehicleList($productSku,$selectAttributes = '*');

    /**
     * @param $product
     * @param $data
     * @return mixed
     */
    public function addProductFitment($product,$data);

    /**
     * @param $product
     * @return mixed
     */
    public function clearProductFitmentData($product);
}