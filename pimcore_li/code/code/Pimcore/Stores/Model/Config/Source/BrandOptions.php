<?php

namespace Pimcore\Stores\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;

class BrandOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var OptionFactory
     */
    protected $optionFactory;
    protected $_websitesData = [
        'bushwack' => [
            'is_default' => 1,
            'label' => 'BUSHWACKER',
            'country' => 'US',
            'timezone' => 'UTC',
            'group' => 'Bushwack',
            'store_views' => [
                'bushwack_en' => [
                    'name' => 'Bushwack English Store',
                    'locale' => 'en_US',
                    'theme' => 'Bushwack/en_US'
                ]
            ]
        ],
        'avs' => [
            'is_default' => 0,
            'label' => 'AVS',
            'country' => 'US',
            'timezone' => 'UTC',
            'group' => 'AVS',
            'store_views' => [
                'avs_en' => [
                    'name' => 'AVS English Store',
                    'locale' => 'en_US',
                    'theme' => 'AVS/en_US'
                ]

            ]
        ],
        'tonno_pro' => [
            'is_default' => 0,
            'label' => 'Tonno Pro',
            'country' => 'US',
            'timezone' => 'UTC',
            'group' => 'Tonno Pro',
            'store_views' => ['tonno_pro_en' => [
                'name' => 'Tonno Pro English Store',
                'locale' => 'en_US',
                'theme' => 'TonnoPro/en_US'
            ]
            ]
        ],
        'rampage' => [
            'is_default' => 0,
            'label' => 'RAMPAGE',
            'country' => 'US',
            'timezone' => 'UTC',
            'group' => 'Rampage',
            'store_views' => ['rampage_en' => [
                'name' => 'Rampage English Store',
                'locale' => 'en_US',
                'theme' => 'Rampage/en_US'
            ]
            ]
        ],
        'lund' => [
            'is_default' => 0,
            'label' => 'LUND',
            'country' => 'US',
            'timezone' => 'UTC',
            'group' => 'LUND',
            'store_views' => [
                'lund_en' => [
                    'name' => 'LUND English Store',
                    'locale' => 'en_US',
                    'theme' => 'LUND/en_US'
                ]
            ]
        ],
        'amp' => [
            'is_default' => 0,
            'label' => 'AMP RESEARCH',
            'country' => 'US',
            'timezone' => 'UTC',
            'group' => 'AMP',
            'store_views' => [
                'amp_en' => [
                    'name' => 'AMP English Store',
                    'locale' => 'en_US',
                    'theme' => 'AMP/en_US'
                ]
            ]
        ],
        'rnl' => [
            'is_default' => 0,
            'label' => 'ROLL N LOCK',
            'country' => 'US',
            'timezone' => 'UTC',
            'group' => 'RnL',
            'store_views' => ['rnl_en' => [
                'name' => 'RnL English Store',
                'locale' => 'en_US',
                'theme' => 'RnL/en_US'
            ]
            ]
        ],
        'li' => [
            'is_default' => 0,
            'label' => 'LI',
            'country' => 'US',
            'timezone' => 'UTC',
            'group' => 'LI',
            'store_views' => ['li_en' => [
                'name' => 'LI English Store',
                'locale' => 'en_US',
                'theme' => 'LI/en_US'
            ]]
        ],
        'acxn' => [
            'is_default' => 0,
            'label' => 'ACXN',
            'country' => 'US',
            'timezone' => 'UTC',
            'group' => 'ACXN',
            'store_views' => ['acxn_en' => [
                'name' => 'ACXN English Store',
                'locale' => 'en_US',
                'theme' => 'ACXN/en_US'
            ]
            ]
        ],
        'stampede' => [
            'is_default' => 0,
            'label' => 'STAMPEDE',
            'country' => 'US',
            'timezone' => 'UTC',
            'group' => 'Stampede',
            'store_views' => ['stampede_en' => [
                'name' => 'Stampede English Store',
                'locale' => 'en_US',
                'theme' => 'Stampede/en_US'
            ]
            ]
        ],
        'belmor' => [
            'is_default' => 0,
            'label' => 'BELMOR',
            'country' => 'US',
            'timezone' => 'UTC',
            'group' => 'Belmor',
            'store_views' => ['belmor_en' =>
                [
                    'name' => 'Belmor English Store',
                    'locale' => 'en_US',
                    'theme' => 'Belmor/en_US'
                ]
            ]
        ],
        'roadworks' => [
            'is_default' => 0,
            'label' => 'Roadworks',
            'country' => 'US',
            'timezone' => 'UTC',
            'group' => 'Roadworks',
            'store_views' => ['roadworks_en' => [
                'name' => 'Roadworks English Store',
                'locale' => 'en_US',
                'theme' => 'Roadworks/en_US'
            ]
            ]
        ]
    ];

    /**
     * @param OptionFactory             $optionFactory
     */
    public function __construct(
        OptionFactory $optionFactory
    )
    {
        $this->optionFactory = $optionFactory;
    }

    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = $this->getAllBrands();
        return $this->_options;
    }

    /**
     * @return array
     */
    private function getAllBrands()
    {
        $brands = $this->_websitesData;
        $result = [];
        foreach ($brands as $key => $brand) {
            $result[] = ['label' => $brand['label'], 'value' => $brand['label']];
        }
        return $result;
    }

    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string|bool
     */
    public function getOptionText($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }

    /**
     * Retrieve flat column definition
     *
     * @return array
     */
    public function getFlatColumns()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        return [
            $attributeCode => [
                'unsigned' => false,
                'default' => null,
                'extra' => null,
                'type' => Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Custom Attribute Options  ' . $attributeCode . ' column',
            ],
        ];
    }
}
