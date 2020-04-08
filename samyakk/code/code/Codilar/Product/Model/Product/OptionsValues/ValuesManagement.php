<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Product\OptionsValues;


use Codilar\Product\Api\Data\Product\OptionValuesInterface;
use Codilar\Product\Api\Data\Product\OptionValuesInterfaceFactory;
use Codilar\Product\Api\Product\OptionsValues\ValuesManagementInterface;

class ValuesManagement implements ValuesManagementInterface
{
    /**
     * @var OptionValuesInterfaceFactory
     */
    private $optionValuesInterfaceFactory;
    /**
     * @var \Magento\Swatches\Model\SwatchFactory
     */
    private $swatchFactory;
    /**
     * @var \Magento\Swatches\Model\ResourceModel\Swatch
     */
    private $swatchResourceModel;
    /**
     * @var \Magento\Swatches\Helper\Media
     */
    private $media;

    /**
     * ValuesManagement constructor.
     * @param OptionValuesInterfaceFactory $optionValuesInterfaceFactory
     * @param \Magento\Swatches\Model\SwatchFactory $swatchFactory
     * @param \Magento\Swatches\Model\ResourceModel\Swatch $swatchResourceModel
     * @param \Magento\Swatches\Helper\Media $media
     */
    public function __construct(
        OptionValuesInterfaceFactory $optionValuesInterfaceFactory,
        \Magento\Swatches\Model\SwatchFactory $swatchFactory,
        \Magento\Swatches\Model\ResourceModel\Swatch $swatchResourceModel,
        \Magento\Swatches\Helper\Media $media
    )
    {
        $this->optionValuesInterfaceFactory = $optionValuesInterfaceFactory;
        $this->swatchFactory = $swatchFactory;
        $this->swatchResourceModel = $swatchResourceModel;
        $this->media = $media;
    }

    /**
     * @param $options
     * @return \Codilar\Product\Api\Data\Product\OptionValues\ValuesInterface[]
     */
    public function getOptionValues($configOptions)
    {
        $configOptionsArray = [];

        foreach ($configOptions as $option) {
            $configOptionsArray[] = [
                'value' =>  $option['value_index'],
                'label' =>  $option['option_title']
            ];
        }

        $valuesArray = array();
        foreach ($configOptionsArray as $option) {
            /** @var \Codilar\Product\Api\Data\Product\OptionValues\ValuesInterface $values */
            $values = $this->optionValuesInterfaceFactory->create();
            $values->setValueId($option['value']);
            $values->setValueTitle($option['label']);

            $swatch = $this->swatchFactory->create();
            $this->swatchResourceModel->load($swatch, $option['value'], 'option_id');

            $valueLabel = $swatch->getData('value');

            if ($swatch->getData('type') == \Magento\Swatches\Model\Swatch::SWATCH_TYPE_VISUAL_IMAGE) {
                $valueLabel = $this->media->getSwatchMediaUrl() . $valueLabel;
            }

            $values->setValueType($swatch->getData('type'));
            $values->setValueLabel($valueLabel);
            $valuesArray[] = $values;
        }
        return $valuesArray;
    }
}