<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Pimcore\ImportExport\Model;

/**
 * @codeCoverageIgnore
 */
class FitmentData extends \Magento\Framework\Api\AbstractExtensibleObject implements
    \Pimcore\ImportExport\Api\Data\FitmentDataInterface
{
    /**#@+
     * Constants
     */
    const KEY_POSITION = 'fitment_position';
    const KEY_CATEGORY_ID = 'category_id';
    const KEY_BASE_VEHICLE_ID = 'base_vehicle_id';
    const KEY_YEAR_ID = 'year_id';
    const KEY_NO_OF_DOORS = 'no_of_doors';
    const KEY_MAKE_NAME = 'make_name';
    const KEY_MODEL_NAME = 'model_name';
    const KEY_SUB_MODEL = 'sub_model';
    const KEY_SUB_DETAIL = 'sub_detail';
    const KEY_BODY_TYPE = 'body_type';
    const KEY_BED_TYPE = 'bed_type';
    const KEY_CAB_TYPE = 'cab_type';
    const KEY_BED_LENGTH = 'bed_length';

    public function getBaseVehicleId()
    {
        return $this->_get(self::KEY_BASE_VEHICLE_ID);
    }

    public function setBaseVehicleId($baseVehicleId)
    {
        return $this->setData(self::KEY_BASE_VEHICLE_ID, $baseVehicleId);
    }

    public function getYearId()
    {
        return $this->_get(self::KEY_YEAR_ID);
    }

    public function setYearId($yearId)
    {
        return $this->setData(self::KEY_YEAR_ID, $yearId);
    }

    public function getMakeName()
    {
        return $this->_get(self::KEY_MAKE_NAME);
    }

    public function setMakeName($makeName)
    {
        return $this->setData(self::KEY_MAKE_NAME, $makeName);
    }

    public function getModelName()
    {
        return $this->_get(self::KEY_MODEL_NAME);
    }

    public function setModelName($modelName)
    {
        return $this->setData(self::KEY_MODEL_NAME, $modelName);
    }

    public function getSubModel()
    {
        return $this->_get(self::KEY_SUB_MODEL);
    }

    public function setSubModel($subModel)
    {
        return $this->setData(self::KEY_SUB_MODEL, $subModel);
    }

    public function getSubDetail()
    {
        return $this->_get(self::KEY_SUB_DETAIL);
    }

    public function setSubDetail($subDetail)
    {
        return $this->setData(self::KEY_SUB_DETAIL, $subDetail);
    }

    public function getBodyType()
    {
        return $this->_get(self::KEY_BODY_TYPE);
    }

    public function setBodyType($bodyType)
    {
        return $this->setData(self::KEY_BODY_TYPE, $bodyType);
    }

    public function getBedType()
    {
        return $this->_get(self::KEY_BED_TYPE);
    }

    public function setBedType($bedType)
    {
        return $this->setData(self::KEY_BED_TYPE, $bedType);
    }

    public function getCabType()
    {
        return $this->_get(self::KEY_CAB_TYPE);
    }

    public function setCabType($cabType)
    {
        return $this->setData(self::KEY_CAB_TYPE, $cabType);
    }

    public function getBedLength()
    {
        return $this->_get(self::KEY_BED_LENGTH);
    }

    public function setBedLength($bedLength)
    {
        return $this->setData(self::KEY_BED_LENGTH, $bedLength);
    }

    public function getNoOfDoors()
    {
        return $this->_get(self::KEY_NO_OF_DOORS);
    }

    public function setNoOfDoors($noOfDoors)
    {
        return $this->setData(self::KEY_NO_OF_DOORS, $noOfDoors);
    }

    public function getFitmentPosition()
    {
        return $this->_get(self::KEY_POSITION);
    }

    public function setFitmentPosition($position)
    {
        return $this->setData(self::KEY_POSITION, $position);
    }

    public function getData($key)
    {
        return $this->_get($key);
    }


    /**
     * {@inheritdoc}
     *
     * @return \Pimcore\ImportExport\Api\Data\FitmentDataExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     *
     * @param \Pimcore\ImportExport\Api\Data\FitmentDataExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Pimcore\ImportExport\Api\Data\FitmentDataExtensionInterface $extensionAttributes
    )
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
