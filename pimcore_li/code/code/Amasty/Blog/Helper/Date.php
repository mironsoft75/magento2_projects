<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Helper;

class Date extends \Magento\Framework\App\Helper\AbstractHelper
{
    const DATE_TIME_PASSED = 'passed';
    const DATE_TIME_DIRECT = 'direct';
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timezoneInterface;
    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    private $resolverInterface;
    /**
     * @var Settings
     */
    private $helperSettings;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Locale\ResolverInterface $resolverInterface,
        \Amasty\Blog\Helper\Settings $helperSettings,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface
    ) {
        parent::__construct($context);
        $this->timezoneInterface = $timezoneInterface;
        $this->resolverInterface = $resolverInterface;
        $this->helperSettings = $helperSettings;
    }

    /**
     * Retrieves global timezone
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezoneInterface->getDefaultTimezone();
    }

    public function getTimeZoneOffset($isMysql = false)
    {
        $date = new \Zend_Date();
        $date->setTimezone($this->getTimezone());
        if ($isMysql){
            $offsetInt = -$date->getGmtOffset();
            $offset = ($offsetInt >= 0 ? '+' : '-').sprintf( '%02.0f', round( abs($offsetInt/3600) )).':'.( sprintf('%02.0f', abs( round( ( abs( $offsetInt ) - round( abs( $offsetInt / 3600 )  ) * 3600 ) / 60 ) ) ) );
            return $offset;
        } else {
            return $date->getGmtOffset();
        }
    }
    
    protected function _processTimezone(\Zend_Date $date)
    {
        $date->subSecond($this->getTimezoneOffset());
        return $date;
    }

    public function renderTime($datetime, $missTimezone = false)
    {
        if ($datetime instanceof \Zend_Date){
            $date = $datetime;
        } else {
            $date = new \Zend_Date($datetime, \Zend_Date::ISO_8601, $this->resolverInterface->getLocale());
        }

        if (!$missTimezone){
            $date = $this->_processTimezone($date);
        }
        return $date->toString(\Zend_Date::TIME_SHORT);
    }

    public function getHumanizedDate(\Zend_Date $date)
    {
        $nowDate = new \Zend_Date();
        $timestamp = $nowDate->getTimestamp() - $date->getTimestamp();

        if ($date->isToday() || ($timestamp <= 0)){
            return __("Today");
        } elseif ($date->isYesterday()) {
            return __("Yesterday");
        } else {

            # Nice correction
            $days = round( $timestamp / (3600 * 24) );
            $months = round( $timestamp / (3600 * 24 * 30) );
            $years = round( $timestamp / (3600 * 24 * 30 * 12) );

            if ($days < 30){

                if ($days == 1){
                    return __("%1 days ago", $days);
                } else {
                    return __("%1 day ago", $days);
                }

            } elseif ($months < 12) {

                if ($months == 1){
                    return __("%1 month ago", $months);
                } else {
                    return __("%1 months ago", $months);
                }

            } else {

                if ($years == 1){
                    return __("%1 year ago", $years);
                } else {
                    return __("%1 years ago", $years);
                }
            }
        }
    }

    public function renderDate($datetime, $missTimezone = false, $forceDirect = false)
    {
        if ($datetime instanceof \Zend_Date){
            $date = $datetime;
        } else {
            $date = new \Zend_Date($datetime, \Zend_Date::ISO_8601, $this->resolverInterface->getLocale());
        }

        if (!$missTimezone){
            $date = $this->_processTimezone($date);
        }

        if ($forceDirect || ($this->helperSettings->getDateFormat() == self::DATE_TIME_DIRECT)){
            return $date->toString(\Zend_Date::DATE_LONG);
        } else {

            return $this->getHumanizedDate($date);
        }
    }
}