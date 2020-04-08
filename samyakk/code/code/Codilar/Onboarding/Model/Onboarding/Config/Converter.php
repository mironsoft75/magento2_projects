<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Onboarding\Model\Onboarding\Config;


use Magento\Framework\Config\ConverterInterface;

class Converter implements ConverterInterface
{

    /**
     * Convert config
     *
     * @param \DOMDocument $source
     * @return array
     */
    public function convert($source)
    {
        $sectionData = [];
        $sections = $source->getElementsByTagName('section');
        /** @var \DOMElement $section */
        foreach ($sections as $section) {
            $sectionName = $section->getAttribute('name');
            $sectionClass = $section->getElementsByTagName('service')->item(0)->textContent;
            $sectionMethod = $section->getElementsByTagName('method')->item(0)->textContent;
            $sectionData[] = [
                'name' => $sectionName,
                'class' => $sectionClass,
                'method' => $sectionMethod
            ];
        }
        return [
            'sections' => $sectionData
        ];
    }
}