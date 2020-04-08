<?php


namespace Pimcore\Aces\Model;


use Pimcore\Aces\Api\AcesProductsManagementInterface;
use Pimcore\Aces\Api\AcesProductsRepositoryInterface;
use Pimcore\Aces\Model\AcesProductsFactory;

class AcesProductsManagement implements AcesProductsManagementInterface
{
    const SKU = 'sku';


    /**
     * @var AcesProductsRepositoryInterface
     */
    private $acesProductsRepository;
    /**
     * @var \Pimcore\Aces\Model\AcesProductsFactory
     */
    private $acesProductsFactory;

    public function __construct(
        AcesProductsRepositoryInterface $acesProductsRepository,
        AcesProductsFactory $acesProductsFactory
    )
    {
        $this->acesProductsRepository = $acesProductsRepository;
        $this->acesProductsFactory = $acesProductsFactory;
    }

    public function addProductFitment($product, $data)
    {
        if (empty($data['base_vehicle_id'])) {
            return $this;
        }
        $data = $this->acesProductsFactory->create()->addData($data);
        $data[self::SKU] = $product->getData(self::SKU);
        $this->acesProductsRepository->save($data);
    }

    /**
     * @param $product
     * @return mixed|void
     */
    public function clearProductFitmentData($product)
    {
        $collection = $this->getCollection()->addFieldToFilter(self::SKU, $product->getData(self::SKU));
        $collection->walk('delete');
    }

    /**
     * @param $productSku
     * @return $this|array
     */
    public function getAcesVehicleList($productSku, $selectAttributes = '*')
    {

        $acesProductsCollection = $this->getCollection();
        $acesProductsCollection->addFieldToSelect($selectAttributes);
        $acesProductsCollection->addFieldToFilter('sku', $productSku);
        $list = [];
        foreach ($acesProductsCollection as $data) {
            $list[] = $data->getData();
        }
        return $list;
    }

    private function getCollection()
    {
        return $this->acesProductsRepository->getCollection();
    }
}