<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Plugins\Model;

use Codilar\BannerSlider\Helper\Data;
use Magestore\Bannerslider\Model\Banner as Subject;

class Banner
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * Banner constructor.
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    )
    {
        $this->helper = $helper;
    }


    /**
     * @param Subject $subject
     * @return Subject
     */
    public function beforeBeforeSave(
        Subject $subject
    )
    {
        $subject->setClickUrl($this->helper->getOnClickUrl($subject));
        return $subject;
    }
}
