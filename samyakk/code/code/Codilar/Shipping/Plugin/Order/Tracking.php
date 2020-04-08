<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Shipping\Plugin\Order;

use Magento\Shipping\Block\Adminhtml\Order\Tracking as Subject;

class Tracking
{
    /**
     * @param Subject $subject
     * @param array $result
     * @return array
     */
    public function afterGetCarriers(Subject $subject, array $result) {
        $result['blue_dart'] = "Blue Dart";
        $result['dtdc'] = "DTDC";
        return $result;
    }
}