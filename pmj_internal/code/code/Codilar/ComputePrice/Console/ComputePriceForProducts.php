<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/8/19
 * Time: 11:09 AM
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
use Magento\Framework\App\Action\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Codilar\ComputePrice\Helper\PriceHelper;


/**
 * Class ComputePriceForProducts
 * @package Codilar\ComputePrice\Console
 */
class ComputePriceForProducts extends Command
{
    const NO = "No";
    const DELETE_PRODUCT = "delete_product";
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
    protected $_priceHelper;
    /**
     * Collection Factory
     *
     * @var CollectionFactory
     */
    protected $_collectionFactory;
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
     * ComputePriceForProducts constructor.
     * @param CollectionFactory $collectionFactory
     * @param Data $data
     * @param State $state
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Data $data,
        State $state,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository,
        Context $context,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        priceHelper $priceHelper,
        Escaper $escaper
    )
    {
        $this->_collectionFactory = $collectionFactory;
        $this->_logger = $logger;
        $this->_priceHelper = $data;
        $this->_state = $state;
        $this->_storeManager = $storeManager;
        $this->_productRepositoryInterface = $productRepository;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->_escaper = $escaper;
        $this->mailHelper = $priceHelper;
        parent::__construct();
    }

    /**
     * configure
     */
    protected function configure()
    {
        $this->setName('codilar:computeprice:forproducts')
            ->setDescription('Codilar Compute Price For Products');
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
                ->setTemplateIdentifier('assign_categories')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_ADMINHTML,
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
                        $product = $this->_productRepositoryInterface
                            ->get($productInfo['sku']);
                        $product->setStockData(
                            ['qty' => $product->getPcs(),
                                'is_in_stock' => $product->getPcs() > 0]
                        );
                        $this->_storeManager->setCurrentStore($store->getId());
                        $this->_productRepositoryInterface->save($product);
                        if ($product->getAttributeText(self::DELETE_PRODUCT) == self::NO) {
                            $this->_priceHelper->updateIndividualProductStockData($product->getId());
                        }
                    }
                }
            }
            foreach ($stores as $store) {
                $this->_priceHelper->updateProductAttributes($store);
                $this->_priceHelper->displayProducts($store);
            }
            if ($this->mailHelper->sendMail()) {
                $this->sendMail();
            }
            return true;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }
}