<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Pimcore\ImportExport\Model;
use Pimcore\ImportExport\Api\Data\DigitalAssetsInterface;

/**
 * @codeCoverageIgnore
 */
class DigitalAssets extends \Magento\Framework\Api\AbstractExtensibleObject implements
    DigitalAssetsInterface
{
    /**#@+
     * Constants
     */
    const KEY_URL = 'url';
    const KEY_MEDIA_TYPE = 'media_type';
    const KEY_PLACEHOLDERS = 'placeholders';
    const KEY_LABEL = 'label';

    public function getUrl()
    {
        return $this->_get(self::KEY_URL);
    }

    public function setUrl($url)
    {
        return $this->setData(self::KEY_URL, $url);
    }

    public function getMediaType()
    {
        return $this->_get(self::KEY_MEDIA_TYPE);
    }

    public function setMediaType($mediaType)
    {
        return $this->setData(self::KEY_MEDIA_TYPE, $mediaType);
    }

    public function getPlaceholders()
    {
        return $this->_get(self::KEY_PLACEHOLDERS);
    }

    public function setPlaceholders($placeholders)
    {
        return $this->setData(self::KEY_PLACEHOLDERS, $placeholders);
    }

    public function getLabel()
    {
        return $this->_get(self::KEY_LABEL);
    }

    public function setLabel($label)
    {
        return $this->setData(self::KEY_LABEL, $label);
    }


    public function getData($key)
    {
        return $this->_get($key);
    }


    /**
     * {@inheritdoc}
     *
     * @return \Pimcore\ImportExport\Api\Data\DigitalAssetsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * {@inheritdoc}
     *
     * @param \Pimcore\ImportExport\Api\Data\DigitalAssetsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Pimcore\ImportExport\Api\Data\DigitalAssetsExtensionInterface $extensionAttributes
    )
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
