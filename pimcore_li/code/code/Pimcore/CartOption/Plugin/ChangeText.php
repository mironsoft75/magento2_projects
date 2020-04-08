<?php
/**
 * Created by PhpStorm.
 * User: pimcore
 * Date: 1/10/18
 * Time: 5:46 PM
 */

/**
 * Class ChangeText
 * @package Pimcore\CartOption\Plugin
 */


namespace Pimcore\CartOption\Plugin;


class ChangeText
{
    public function afterGetLabel(\Magento\Customer\Block\Account\AuthorizationLink $subject)
    {
        return $subject->isLoggedIn() ? __('Logout') : __('Login');
    }
}