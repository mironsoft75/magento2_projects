<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20/8/19
 * Time: 2:43 PM
 */

namespace Codilar\ComputePrice\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Codilar\ProductImport\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\State;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ResourceModel\Product\Action as ProductAction;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Codilar\ComputePrice\Helper\PriceHelper;
use Magento\Framework\Event\Manager;


/**
 * Class ComputePriceForBomTables
 * @package Codilar\ComputePrice\Console
 */
class ComputePriceForBomTables extends Command
{
    /**
     * Logger Interface
     *
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * Price Helper
     *
     * @var Data
     */
    private $_priceHelper;
    /**
     * Collection Factory
     *
     * @var CollectionFactory
     */
    private $_collectionFactory;
    /**
     * State
     *
     * @var State
     */
    private $_state;
    /**
     * StoreManagerInterface
     *
     * @var StoreManagerInterface
     */
    private $_storeManager;
    /**
     * ProductRepositoryInterface
     *
     * @var ProductRepositoryInterface
     */
    private $_productRepositoryInterface;
    /**
     * ProductAction
     *
     * @var ProductAction
     */
    protected $productAction;
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;
    /**
     * Price Helper
     *
     * @var PriceHelper
     */
    protected $mailHelper;
    /**
     * Event Manager
     *
     * @var Manager
     */
    protected $eventManager;


    /**
     * ComputePriceForBomTables constructor.
     * @param CollectionFactory $collectionFactory
     * @param Data $data
     * @param State $state
     * @param StoreManagerInterface $storeManager
     * @param ProductAction $productAction
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     * @param Context $context
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param PriceHelper $priceHelper
     * @param Manager $eventManager
     * @param Escaper $escaper
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Data $data,
        State $state,
        StoreManagerInterface $storeManager,
        ProductAction $productAction,
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository,
        Context $context,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        priceHelper $priceHelper,
        Manager $eventManager,
        Escaper $escaper
    )
    {
        parent::__construct();
        $this->_collectionFactory = $collectionFactory;
        $this->_logger = $logger;
        $this->_priceHelper = $data;
        $this->_state = $state;
        $this->_storeManager = $storeManager;
        $this->productAction = $productAction;
        $this->_productRepositoryInterface = $productRepository;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->_escaper = $escaper;
        $this->eventManager = $eventManager;
        $this->mailHelper = $priceHelper;
    }

    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName('codilar:computeprice:forbomtables')
            ->setDescription('Codilar Compute Price For Products based on Bom Tables');
        parent::configure();
    }

    /**
     * Send Mail
     */
    public function sendMail()
    {
        $this->inlineTranslation->suspend();
        try {
            $post = [
                'name' => $this->mailHelper->senderName(),
                'email' => $this->mailHelper->senderEmail(),
            ];
            $sender = [
                'name' => $this->_escaper->escapeHtml($post['name']),
                'email' => $this->_escaper->escapeHtml($post['email']),
            ];
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('compute_price')// this code we have mentioned in the email_templates.xml
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_ADMINHTML, // this is using frontend area to get the template file
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars(['data' => $post])
                ->setFrom($sender)
                ->addTo($this->mailHelper->receiverEmail())
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();
            $this->_logger->critical($e);
        }
    }

    /**
     * Execute
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool|int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $entityData="done";
            $this->_state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
            /**
             * Product Collection
             *
             * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $products
             */
            $products = $this->_collectionFactory->create();
            $products->addAttributeToSelect('*');
            $stores = $this->_storeManager->getStores(true);
            if ($products->getSize()) {
                foreach ($products as $productInfo) {
                    foreach ($stores as $store) {
                        $this->_storeManager->setCurrentStore($store->getId());
                        $metalPrice = $this->_priceHelper
                            ->metalPriceCalculation($productInfo, $store->getId());
                        $diamondPrice = $this->_priceHelper
                            ->stonePricecalculation($productInfo, $store->getId());
                        $grandTotal = $metalPrice + $diamondPrice;
                        $gstPercentage = $this->_priceHelper->gstPercentage();
                        if ($this->_priceHelper->computeGst()
                            && isset($gstPercentage)
                        ) {
                            $gstAmount = ($grandTotal * $gstPercentage) / 100;
                            $total = $grandTotal + $gstAmount;
                            $price = $total;
                        } else {
                            $price = $grandTotal;
                        }
                        $updateAttributes['price'] = $price;
                        $this->productAction->updateAttributes(
                            [$productInfo->getId()],
                            $updateAttributes,
                            $this->_storeManager->getStore()->getId()
                        );
                    }
                }
            }
            foreach ($stores as $store){
                $this->_priceHelper->updateProductAttributes($store);
            }
            if ($this->mailHelper->sendMail()) {
                $this->sendMail();
            }
            $this->eventManager->dispatch('codilar_product_price_compute_after', ['newData' => $entityData]);
            return true;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }
}