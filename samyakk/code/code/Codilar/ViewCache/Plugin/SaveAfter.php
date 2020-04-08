<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 29/7/19
 * Time: 4:00 PM
 */

namespace Codilar\ViewCache\Plugin;

use Codilar\ViewCache\Helper\Data;

class SaveAfter
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * SaveAfter constructor.
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    public function afterExecute(\Magento\Cms\Controller\Adminhtml\Block\Save $subject, $result)
    {
        $data = $subject->getRequest()->getParams();
        $showInHomepage = $data['show_in_homepage'];
        if ($showInHomepage === "1") {
            $tag = "home";
            $this->helper->urlExecute($tag);
        }
        return $result;
    }
}
