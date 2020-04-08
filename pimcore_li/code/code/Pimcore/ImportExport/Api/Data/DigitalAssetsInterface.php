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
interface DigitalAssetsInterface extends ExtensibleDataInterface
{
    /**
     * @return string|null
     */
    public function getUrl();

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url);

    /**
     * @return string|null
     */
    public function getMediaType();

    /**
     * @param string $mediaType
     * @return $this
     */
    public function setMediaType($mediaType);

    /**
     * @return string|null
     */
    public function getLabel();

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label);

    /**
     * @return mixed
     */
    public function getPlaceholders();

    /**
     * @param array $placeholders
     * @return $this
     */
    public function setPlaceholders($placeholders);

    /**
     * @param string $key
     * @return $this
     */
    public function getData($key);


    /**
     * Retrieve existing extension attributes object.
     *
     * @return \Pimcore\ImportExport\Api\Data\DigitalAssetsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Pimcore\ImportExport\Api\Data\DigitalAssetsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Pimcore\ImportExport\Api\Data\DigitalAssetsExtensionInterface $extensionAttributes
    );
}
