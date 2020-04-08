<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/4/19
 * Time: 9:58 AM
 */

namespace Codilar\Core\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Store\Model\WebsiteFactory;
use Magento\Store\Model\ResourceModel\Website;
use Magento\Store\Model\GroupFactory;
use Magento\Store\Model\ResourceModel\Group;
use Magento\Store\Model\ResourceModel\Store;
use Magento\Config\Model\ConfigFactory;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreFactory;
use Psr\Log\LoggerInterface;

/**
 * Class UpgradeData
 * @package Codilar\Core\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var WebsiteFactory
     */
    private $websiteFactory;
    /**
     * @var Website
     */
    private $websiteResourceModel;
    /**
     * @var
     */
    private $storeFactory;
    /**
     * @var ManagerInterface
     */
    private $eventManager;
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
     * @var ConfigFactory
     */
    private $configFactory;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var array
     */
    protected $_websitesData = [
        'pmjjewels' => [
            'is_default' => 0,
            'label' => 'Pmjjewels',
            'country' => 'IN',
            'timezone' => 'Asia/Kolkata',
            'group' => 'Pmjjewels',
            'store_views' => [
                'pmjjewels' => [
                    'name' => 'Pmjjewels Homepage',
                    'locale' => 'en_US',
                    'theme' => 'Pmj/default'
                ]
            ]
        ]
    ];

    /**
     * UpgradeData constructor.
     * @param Website $website
     * @param WebsiteFactory $websiteFactory
     * @param GroupFactory $groupFactory
     * @param Group $group
     * @param Store $store
     * @param ConfigFactory $configFactory
     * @param ManagerInterface $manager
     * @param StoreFactory $storeFactory
     * @param LoggerInterface $logger
     */
    public function __construct
    (
        Website $website,
        WebsiteFactory $websiteFactory,
        GroupFactory $groupFactory,
        Group $group,
        Store $store,
        ConfigFactory $configFactory,
        ManagerInterface $manager,
        StoreFactory $storeFactory,
        LoggerInterface $logger
    )
    {
        $this->websiteResourceModel = $website;
        $this->websiteFactory = $websiteFactory;
        $this->groupFactory = $groupFactory;
        $this->groupResourceModel = $group;
        $this->storeResourceModel = $store;
        $this->configFactory = $configFactory;
        $this->eventManager = $manager;
        $this->storeFactory = $storeFactory;
        $this->logger = $logger;

    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $this->_createPmjWebsite();
        }
    }

    /**
     * createsPmjwebsite
     */
    public function _createPmjWebsite()
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
                        $this->assignWebsiteCurrency($data['country'], $data['timezone'], $storeData['locale'], $website, $data['label']);
                        // Trigger event to insert some data to the sales_sequence_meta table (fixes bug place order in checkout)
                        $this->eventManager->dispatch('store_add', ['store' => $store]);
                        $themeData[$store->getId()] = $storeData['theme'];
                    }
                }

            }
        } catch (\Exception $e) {
            $this->logger->critical('Error in ' . __CLASS__);
            $this->logger->critical($e->getMessage());
        }

    }

    protected function assignWebsiteCurrency($country, $timeZone, $locale, $website, $storeName)
    {
        try {
            $configData = [
                'section' => 'general',
                'website' => $website,
                'store' => null,
                'groups' => [
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
        } catch (\Exception $e) {
            $this->logger->critical('Error in ' . __CLASS__);
            $this->logger->critical($e->getMessage());
        }

    }
}