<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Plugin;

use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\DataObject;

class WysiwygConfigPlugin
{
    /**
     * Adds parameter to allow correct embedding of images
     *
     * @param Config $subject
     * @param DataObject $config
     *
     * @return DataObject
     */
    public function afterGetConfig(Config $subject, DataObject $config)
    {
        $config->addData(['add_directives' => true]);

        return $config;
    }
}