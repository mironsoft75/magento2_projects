<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\System;

class Template extends \Magento\Backend\Block\Template
{

    /**
     * Prepare AngularJs Template string
     *
     * @param $template
     * @param array $data
     * @return bool
     */
    public function prepareAngularJsTemplate($template, $data = array())
    {
        return str_replace(array("'", "\n", "\r"), array("\\'", "", ""), $this->compileTemplate($template, $data));
    }

}