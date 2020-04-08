<?php
/**
 * @package   CategoryImporter
 * @author    Splash
 */

namespace Codilar\DeleteProducts\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Codilar\ProductImport\Helper\Data;
use Magento\Framework\App\State;
use Psr\Log\LoggerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Codilar\ComputePrice\Helper\PriceHelper;

/**
 * Class DeleteProducts
 *
 * @package Codilar\DeleteProducts\Console
 */
class DeleteProducts extends Command
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
     * State
     *
     * @var State
     */
    private $_state;
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
     * DeleteProducts constructor.
     * @param Data $data
     * @param State $state
     * @param LoggerInterface $logger
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param PriceHelper $priceHelper
     * @param Escaper $escaper
     */
    public function __construct(
        Data $data,
        State $state,
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        priceHelper $priceHelper,
        Escaper $escaper
    )
    {
        $this->_logger = $logger;
        $this->_priceHelper = $data;
        $this->_state = $state;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->_escaper = $escaper;
        $this->mailHelper = $priceHelper;
        parent::__construct();
    }

    /**
     * configure
     */
    protected function configure()
    {
        $this->setName('codilar:deleteproducts')
            ->setDescription('Codilar Delete Products');
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
                ->setTemplateIdentifier('delete_products')
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
            $this->_priceHelper->deleteProducts();
            if ($this->mailHelper->sendMail()) {
                $this->sendMail();
            }
            return true;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }

}