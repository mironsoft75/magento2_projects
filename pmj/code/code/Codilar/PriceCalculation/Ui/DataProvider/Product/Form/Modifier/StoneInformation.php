<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 27/12/18
 * Time: 4:59 PM
 */

namespace Codilar\PriceCalculation\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Textarea;

class StoneInformation extends AbstractModifier
{
    const ATTRACTION_HIGHLIGHTS_FIELD = 'internal_stone_name';

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var array
     */
    private $meta = [];

    /**
     * @var string
     */
    protected $scopeName;

    /**
     * @param LocatorInterface $locator
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager,
        $scopeName = ''
    )
    {
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->scopeName = $scopeName;
    }

    /**
     * @param $stones
     * @return array
     */
    public function removeBrackets($stones)
    {
        $newStones = array();
        foreach ($stones as $stone) {
            array_push($newStones, trim(preg_replace("/[{}]/", "", $stone)));
        }
        return $newStones;
    }

    /**
     * @param $product
     */
    public function getStoneDetails($product)
    {
        $internalStoneDetails = [];
        $stoneInformation = $product->getStoneInformation();
        if ($stoneInformation) {
            $stones = explode('},', $stoneInformation);
            if (count(explode('{', $stones[0])) >= 1) {
                $explodedData = explode('{', $stones[0]);
                if (isset($explodedData[1])) {
                    $stones[0] = $explodedData[1];
                    $stoneDetails = $this->removeBrackets($stones);
                    foreach ($stoneDetails as $individual_stones) {
                        $equalSeperated = $this->getEqualSeperatedValue($individual_stones);
                        $internalStoneDetails[] = $equalSeperated;
                    }
                }
            }
        }
        return $internalStoneDetails;
    }

    /**
     * @param $individualStones
     * @param $emptyValues
     * @return array
     */
    public function getEqualSeperatedValue($individualStones)
    {
        $stones = explode(',', $individualStones);
        $stoneValues = [];
        foreach ($stones as $stone) {
            $stoneArray = explode("=", $stone);
            $stoneValues[$stoneArray[0]] = $stoneArray[1];
        }
        return $stoneValues;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $model = $this->locator->getProduct();
        $modelId = $model->getId();
        $stoneDetails = $this->getStoneDetails($model);
        $path = $modelId . '/' . self::DATA_SOURCE_DEFAULT . '/' . self::ATTRACTION_HIGHLIGHTS_FIELD;
        $data = $this->arrayManager->set($path, $data, $stoneDetails);
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        $this->initAttractionHighlightFields();
        return $this->meta;
    }

    /**
     * Customize attraction highlights field
     *
     * @return $this
     */
    protected function initAttractionHighlightFields()
    {
        $highlightsPath = $this->arrayManager->findPath(
            self::ATTRACTION_HIGHLIGHTS_FIELD,
            $this->meta,
            null,
            'children'
        );

        if ($highlightsPath) {
            $this->meta = $this->arrayManager->merge(
                $highlightsPath,
                $this->meta,
                $this->initHighlightFieldStructure($highlightsPath)
            );
            $this->meta = $this->arrayManager->set(
                $this->arrayManager->slicePath($highlightsPath, 0, -3)
                . '/' . self::ATTRACTION_HIGHLIGHTS_FIELD,
                $this->meta,
                $this->arrayManager->get($highlightsPath, $this->meta)
            );
            $this->meta = $this->arrayManager->remove(
                $this->arrayManager->slicePath($highlightsPath, 0, -2),
                $this->meta
            );
        }

        return $this;
    }


    /**
     * Get attraction highlights dynamic rows structure
     *
     * @param string $highlightsPath
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function initHighlightFieldStructure($highlightsPath)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => 'dynamicRows',
                        'label' => __('Stone Details'),
                        'renderDefaultRecord' => false,
                        'recordTemplate' => 'record',
                        'dataScope' => '',
                        'dndConfig' => [
                            'enabled' => false,
                        ],
                        'disabled' => false,
                        'required' => false,
                        'sortOrder' =>
                            $this->arrayManager->get($highlightsPath . '/arguments/data/config/sortOrder', $this->meta),
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'isTemplate' => true,
                                'is_collection' => true,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'dataScope' => '',
                            ],
                        ],
                    ],
                    'children' => [
                        'internal_stone_name' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Textarea::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Stone Name'),
                                        'dataScope' => 'internal_stone_name',
                                    ],
                                ],
                            ],
                        ],

                        'stone_pcs' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Stone Pcs'),
                                        'dataScope' => 'stone_pcs',
                                    ],
                                ],
                            ],
                        ],
                        'stone_total_wt' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Stone Weight'),
                                        'dataScope' => 'stone_total_wt',
                                    ],
                                ],
                            ],
                        ],

                        'stone_rate' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Stone Rate'),
                                        'dataScope' => 'stone_rate',
                                    ],
                                ],
                            ],
                        ],

                        'price_override' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Price Override'),
                                        'dataScope' => 'price_override',
                                    ],
                                ],
                            ],
                        ],
                        'stone_setting_type' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Stone Type'),
                                        'dataScope' => 'stone_setting_type',
                                    ],
                                ],
                            ],
                        ],

                        'stone_unit' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Stone Unit'),
                                        'dataScope' => 'stone_unit',
                                    ],
                                ],
                            ],
                        ],

                        'diamond_quality' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Diamond Quality'),
                                        'dataScope' => 'diamond_quality',
                                    ],
                                ],
                            ],
                        ],
                        'stone_name_for_customer' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Stone Name For Customer'),
                                        'dataScope' => 'stone_name_for_customer',
                                    ],
                                ],
                            ],
                        ],

                        'stone_shape' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Stone Shape'),
                                        'dataScope' => 'stone_shape',
                                    ],
                                ],
                            ],
                        ],

                        'actionDelete' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => 'actionDelete',
                                        'dataType' => Text::NAME,
                                        'label' => '',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}