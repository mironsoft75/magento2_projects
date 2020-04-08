<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Pimcore\ImportExport\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * @api
 */
interface FitmentDataInterface extends ExtensibleDataInterface
{

    /**
     * @return int|null
     */
    public function getBaseVehicleId();

    /**
     * @param int $baseVehicleId
     * @return $this
     */
    public function setBaseVehicleId($baseVehicleId);

    /**
     * @return string|null
     */
    public function getYearId();

    /**
     * @param string $yearId
     * @return $this
     */
    public function setYearId($yearId);

    /**
     * @return string|null
     */
    public function getMakeName();

    /**
     * @param string $makeName
     * @return $this
     */
    public function setMakeName($makeName);

    /**
     * @return string|null
     */
    public function getModelName();

    /**
     * @param string $modelName
     * @return $this
     */
    public function setModelName($modelName);

    /**
     * @return string|null
     */
    public function getSubModel();

    /**
     * @param string $subModel
     * @return $this
     */
    public function setSubModel($subModel);

    /**
     * @return string|null
     */
    public function getSubDetail();

    /**
     * @param string $subDetail
     * @return $this
     */
    public function setSubDetail($subDetail);

    /**
     * @return string|null
     */
    public function getBodyType();

    /**
     * @param string $bodyType
     * @return $this
     */
    public function setBodyType($bodyType);

    /**
     * @return string|null
     */
    public function getBedType();

    /**
     * @param string $bedType
     * @return $this
     */
    public function setBedType($bedType);

    /**
     * @return string|null
     */
    public function getCabType();

    /**
     * @param string $cabType
     * @return $this
     */
    public function setCabType($cabType);

    /**
     * @return string|null
     */
    public function getBedLength();

    /**
     * @param string $bedLength
     * @return $this
     */
    public function setBedLength($bedLength);

    /**
     * @return string|null
     */
    public function getNoOfDoors();

    /**
     * @param string $noOfDoors
     * @return $this
     */
    public function setNoOfDoors($noOfDoors);

    /**
     * @return string|null
     */
    public function getFitmentPosition();

    /**
     * @param string $position
     * @return $this
     */
    public function setFitmentPosition($position);

    /**
     * @param string $key
     * @return $this
     */
    public function getData($key);


    /**
     * Retrieve existing extension attributes object.
     *
     * @return \Pimcore\ImportExport\Api\Data\FitmentDataExtensionInterface|null
     * @since 101.1.0
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Pimcore\ImportExport\Api\Data\FitmentDataExtensionInterface $extensionAttributes
     * @return $this
     * @since 101.1.0
     */
    public function setExtensionAttributes(
        \Pimcore\ImportExport\Api\Data\FitmentDataExtensionInterface $extensionAttributes
    );
}
