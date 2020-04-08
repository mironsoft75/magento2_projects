<?php
/**
 * Created by salman.
 * Date: 5/9/18
 * Time: 10:54 AM
 * Filename: InstallData.php
 */

namespace Pimcore\Stores\Setup;

use Magento\Config\Model\ConfigFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\View\Design\Theme\ListInterface;
use Magento\Store\Model\GroupFactory;
use Magento\Store\Model\ResourceModel\Group;
use Magento\Store\Model\ResourceModel\Store;
use Magento\Store\Model\ResourceModel\Website;
use Magento\Store\Model\StoreFactory;
use Magento\Store\Model\WebsiteFactory;
use Magento\Theme\Model\Config;
use Magento\Theme\Model\ResourceModel\Theme\CollectionFactory;
use Psr\Log\LoggerInterface;

/**
 * Class InstallData
 * @package Pimcore\Stores\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * Area
     */
    const THEME_AREA = 'frontend';
    /**
     * @var array
     */
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
     * @var WebsiteFactory
     */
    private $websiteFactory;
    /**
     * @var Website
     */
    private $websiteResourceModel;
    /**
     * @var StoreFactory
     */
    private $storeFactory;
    /**
     * @var GroupFactory
     */
    private $groupFactory;
    /**
     * @var Group
     */
    private $groupResourceModel;
    /**
     * @var Store
     */
    private $storeResourceModel;
    /**
     * @var ManagerInterface
     */
    private $eventManager;
    /**
     * @var CollectionFactory
     */
    private $themeCollectionFactory;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ConfigFactory
     */
    private $configFactory;
    /**
     * @var ListInterface
     */
    private $themeList;

    /**
     * InstallData constructor.
     * @param WebsiteFactory    $websiteFactory
     * @param Website           $websiteResourceModel
     * @param Store             $storeResourceModel
     * @param Group             $groupResourceModel
     * @param StoreFactory      $storeFactory
     * @param GroupFactory      $groupFactory
     * @param ManagerInterface  $eventManager
     * @param CollectionFactory $themeCollectionFactory
     * @param Config            $config
     * @param LoggerInterface   $logger
     * @param ConfigFactory     $configFactory
     * @param ListInterface     $themeList
     */
    public function __construct(
        WebsiteFactory $websiteFactory,
        Website $websiteResourceModel,
        Store $storeResourceModel,
        Group $groupResourceModel,
        StoreFactory $storeFactory,
        GroupFactory $groupFactory,
        ManagerInterface $eventManager,
        CollectionFactory $themeCollectionFactory,
        Config $config,
        LoggerInterface $logger,
        ConfigFactory $configFactory,
        ListInterface $themeList
    )
    {
        $this->websiteFactory = $websiteFactory;
        $this->websiteResourceModel = $websiteResourceModel;
        $this->storeFactory = $storeFactory;
        $this->groupFactory = $groupFactory;
        $this->groupResourceModel = $groupResourceModel;
        $this->storeResourceModel = $storeResourceModel;
        $this->eventManager = $eventManager;
        $this->themeCollectionFactory = $themeCollectionFactory;
        $this->config = $config;
        $this->logger = $logger;
        $this->configFactory = $configFactory;
        $this->themeList = $themeList;
    }


    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     * @return void
     * @throws \Exception
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        try {

            $themeData = [];
            $websitesData = $this->_websitesData;
            foreach ($websitesData as $code => $data) {
                /** @var \Magento\Store\Model\Website $website */
                $website = $this->websiteFactory->create();
                $website->load($code);
                if (!$website->getId()) {
                    $website->setCode($code);
                    $website->setName($data['label']);
                    $data['is_default'] ? $website->setIsDefault($data['is_default']) : NULL;
                    $this->websiteResourceModel->save($website);
                }
                /** @var \Magento\Store\Model\Group $group */
                $group = $this->groupFactory->create();
                $group->load($code, 'code');
                if ($website->getId() && !$group->getId()) {
                    $group->setCode($code);
                    $group->setWebsiteId($website->getWebsiteId());
                    $group->setName($data['group']);
                    $group->setRootCategoryId(2);
                    $this->groupResourceModel->save($group);
                    $website->setDefaultGroupId($group->getId());
                    $this->websiteResourceModel->save($website);
                }
                $storesData = $data['store_views'];
                foreach ($storesData as $storeCode => $storeData) {
                    /** @var  \Magento\Store\Model\Store $store */
                    $store = $this->storeFactory->create();
                    $store->load($storeCode);
                    if (!$store->getId()) {
                        $group = $this->groupFactory->create();
                        $group->load($data['group'], 'name');
                        $store->setCode($storeCode);
                        $store->setName($storeData['name']);
                        $store->setWebsite($website);
                        $store->setGroupId($group->getId());
                        $store->setData('is_active', '1');
                        $this->storeResourceModel->save($store);
                        $group->setDefaultStoreId($store->getId());
                        $this->groupResourceModel->save($group);
                        $this->assignWebsiteCurrency($data['country'],$data['timezone'],$storeData['locale'],$website,$data['label']);
                        // Trigger event to insert some data to the sales_sequence_meta table (fixes bug place order in checkout)
                        $this->eventManager->dispatch('store_add', ['store' => $store]);
                        $themeData[$store->getId()] = $storeData['theme'];
                    }
                }

            }
            /*setting theme*/
            /*if(!empty($themeData)){
                foreach ($themeData as $storeId => $themePath){
                    $this->assignFrontendTheme($storeId,self::THEME_AREA . '/' . $themePath);
                }
            }*/
        } catch (\Exception $e) {
            $this->logger->critical('Error in ' . __CLASS__);
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * @param $country
     * @param $timeZone
     * @param $locale
     * @param $website
     * @param $storeName
     * @throws \Exception
     */
    protected function assignWebsiteCurrency($country,$timeZone,$locale,$website,$storeName)
    {
        $configData = [
            'section' => 'general',
            'website' => $website,
            'store'   => null,
            'groups'  => [
                'country' => [
                    'fields' => [
                        'allow' => [
                            'value' => $country,
                        ],
                        'default' => [
                            'value' => $country,
                        ],
                    ],
                ],
                'locale' => [
                    'fields' => [
                        'timezone' => [
                            'value' => $timeZone,
                        ],
                        'code' => [
                            'value' => $locale,
                        ],
                    ],
                ],
                'store_information' => [
                    'fields' => [
                        'name' => [
                            'value' => $storeName,
                        ]
                    ],
                ],
            ],
        ];
        /** @var \Magento\Config\Model\Config $configModel */
        $configModel = $this->configFactory->create(['data' => $configData]);
        $configModel->save();
    }

    /**
     * @param $storeId
     * @param $themepath
     */
    protected function assignFrontendTheme($storeId,$themepath)
    {
        /** @var \Magento\Framework\View\Design\ThemeInterface $theme */
        $theme = $this->themeList->getThemeByFullPath($themepath);

        if ($theme->getId()) {
            /**
             * @var \Magento\Theme\Model\Theme $theme
             */
            $this->config->assignToStore(
                $theme,
                [$storeId],
                ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            );
        }

    }
}