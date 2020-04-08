<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Helper\Data;

class Layout extends \Amasty\Blog\Helper\Config
{
    const CONFIG_PATH_LAYOUT = 'layout/%s';
    const CONFIG_PATH_LAYOUT_VALUE = 'layout/%s/%s';

    /**
     * Retrieves Blocks from Config
     *
     * @param $type 'content' | 'sidebar'
     * @return array
     */
    public function getBlocks($type)
    {
        $values = array();
        $config = $this->getConfig();

        //$config[$type]['value'] = $type;

        if (isset($config[$type])) {
            foreach ($config[$type] as $key=>&$item) {
                $item['value'] = $key;
            }

            $values = $config[$type];
        }

        //TODO: Sort blocks by 'sort_order'

        return $values;
    }
}