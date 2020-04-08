<?php
/**
 * Created by pimcore.
 * Date: 15/9/18
 * Time: 4:31 PM
 */

namespace Pimcore\Aces\Model;


use Magento\Framework\Model\AbstractModel;
use Pimcore\Aces\Api\Data\AcesProductsInterface;


class AcesProducts extends AbstractModel implements AcesProductsInterface
{


    const KEY_POSITION = 'position';
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

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(\Pimcore\Aces\Model\ResourceModel\AcesProducts::class);
    }


    public function getBaseVehicleId()
    {
        return $this->getData(self::KEY_BASE_VEHICLE_ID);
    }

    public function setBaseVehicleId($baseVehicleId)
    {
        return $this->setData(self::KEY_BASE_VEHICLE_ID, $baseVehicleId);
    }

    public function getYearId()
    {
        return $this->getData(self::KEY_YEAR_ID);
    }

    public function setYearId($yearId)
    {
        return $this->setData(self::KEY_YEAR_ID, $yearId);
    }

    public function getMakeName()
    {
        return $this->getData(self::KEY_MAKE_NAME);
    }

    public function setMakeName($makeName)
    {
        return $this->setData(self::KEY_MAKE_NAME, $makeName);
    }

    public function getModelName()
    {
        return $this->getData(self::KEY_MODEL_NAME);
    }

    public function setModelName($modelName)
    {
        return $this->setData(self::KEY_MODEL_NAME, $modelName);
    }

    public function getSubModel()
    {
        return $this->getData(self::KEY_SUB_MODEL);
    }

    public function setSubModel($subModel)
    {
        return $this->setData(self::KEY_SUB_MODEL, $subModel);
    }

    public function getSubDetail()
    {
        return $this->getData(self::KEY_SUB_DETAIL);
    }

    public function setSubDetail($subDetail)
    {
        return $this->setData(self::KEY_SUB_DETAIL, $subDetail);
    }

    public function getBodyType()
    {
        return $this->getData(self::KEY_BODY_TYPE);
    }

    public function setBodyType($bodyType)
    {
        return $this->setData(self::KEY_BODY_TYPE, $bodyType);
    }

    public function getBedType()
    {
        return $this->getData(self::KEY_BED_TYPE);
    }

    public function setBedType($bedType)
    {
        return $this->setData(self::KEY_BED_TYPE, $bedType);
    }

    public function getCabType()
    {
        return $this->getData(self::KEY_CAB_TYPE);
    }

    public function setCabType($cabType)
    {
        return $this->setData(self::KEY_CAB_TYPE, $cabType);
    }

    public function getBedLength()
    {
        return $this->getData(self::KEY_BED_LENGTH);
    }

    public function setBedLength($bedLength)
    {
        return $this->setData(self::KEY_BED_LENGTH, $bedLength);
    }

    public function getNoOfDoors()
    {
        return $this->getData(self::KEY_NO_OF_DOORS);
    }

    public function setNoOfDoors($noOfDoors)
    {
        return $this->setData(self::KEY_NO_OF_DOORS, $noOfDoors);
    }

    public function getPosition()
    {
        return $this->getData(self::KEY_POSITION);
    }

    public function setPosition($position)
    {
        return $this->setData(self::KEY_POSITION, $position);
    }

    public function getData($key = '', $index = null)
    {
        return parent::getData($key);
    }

    public function setData($key, $value = null)
    {
        return parent::setData($key, $value);
    }


}