<?php

namespace Pimcore\Aces\Api;


use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface AcesProductsRepositoryInterface
{
    /**
     * @param mixed       $value
     * @param null|string $field
     * @return \Pimcore\Aces\Api\Data\AcesProductsInterface
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null);

    /**
     * @return \Pimcore\Aces\Api\Data\AcesProductsInterface
     */
    public function create();

    /**
     * @param \Pimcore\Aces\Api\Data\AcesProductsInterface $model
     * @return \Pimcore\Aces\Api\Data\AcesProductsInterface
     * @throws LocalizedException
     */
    public function save(\Pimcore\Aces\Api\Data\AcesProductsInterface $model);

    /**
     * @param \Pimcore\Aces\Api\Data\AcesProductsInterface $model
     * @return $this
     * @throws LocalizedException
     */
    public function delete(\Pimcore\Aces\Api\Data\AcesProductsInterface $model);

    /**
     * @return \Pimcore\Aces\Model\ResourceModel\AcesProducts\Collection
     */
    public function getCollection();
}