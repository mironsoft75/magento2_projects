<?php
/**
 *
 * @package     Magento 2
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Onboarding\Api;


interface OnboardingManagementInterface
{
    /**
     * @return \Codilar\Onboarding\Model\Data\AggregatedData
     */
    public function getAggregatedData();
}