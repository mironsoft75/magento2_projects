<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Onboarding\Model;

use Codilar\Onboarding\Api\OnboardingManagementInterface;
use Magento\Framework\App\ObjectManager;

class OnboardingManagement implements OnboardingManagementInterface
{
    /**
     * @var Onboarding\Config
     */
    private $config;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * OnboardingManagement constructor.
     * @param Onboarding\Config $config
     */
    public function __construct(
        Onboarding\Config $config
    )
    {
        $this->objectManager = ObjectManager::getInstance();
        $this->config = $config;
    }

    /**
     * @return Data\AggregatedData
     */
    public function getAggregatedData()
    {
        $sections = $this->config->getSections();
        $response = $this->objectManager->create(Data\AggregatedData::class);
        foreach ($sections as $section) {
            $camelCaseFormattedName = 'set' . lcfirst(str_replace(" ", "", ucwords(str_replace("_", " ", $section['name']))));
            $service = $this->objectManager->create($section['class']);
            $response->{$camelCaseFormattedName}($service->{$section['method']}());
        }
        return $response;
    }
}