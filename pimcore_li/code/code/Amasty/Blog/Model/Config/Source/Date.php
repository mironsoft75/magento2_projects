<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\Config\Source;
use Magento\Framework\Option\ArrayInterface;

class Date implements ArrayInterface
{

    /**
     * @var \Amasty\Blog\Helper\Date
     */
    private $date;

    public function __construct(
        \Amasty\Blog\Helper\Date $date
    ) {
        $this->date = $date;
    }

    public function toOptionArray()
    {
        $date = new \Zend_Date();
        $date->subDay(6)->subHour(5)->subHour(3);

        return [
            ['value' => \Amasty\Blog\Helper\Date::DATE_TIME_PASSED, 'label' => $this->date->getHumanizedDate($date)],
            ['value' => \Amasty\Blog\Helper\Date::DATE_TIME_DIRECT, 'label' => $this->date->renderDate($date, null, true)],
        ];
    }

    public function toArray()
    {
        $date = new \Zend_Date();
        $date->subDay(6)->subHour(5)->subHour(3);

        return [
            \Amasty\Blog\Helper\Date::DATE_TIME_PASSED => $this->date->getHumanizedDate($date),
            \Amasty\Blog\Helper\Date::DATE_TIME_DIRECT => $this->date->renderDate($date, null, true),
        ];
    }
}

