<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Plugins;

class Sitemap
{
    /**
     * @param $subject
     * @return int
     */
    public function beforeGenerateXml($subject)
    {
        return 1;
    }
}
