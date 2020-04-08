<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\OrderHandler\Console;

use Codilar\Checkout\Helper\Order as OrderHelper;
use Codilar\OrderHandler\Helper\EmailHelper;
use Codilar\OrderHandler\Model\Config;
use Codilar\OrderHandler\Model\Order\Status\Pool;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Magento\Sales\Api\OrderManagementInterface;
use Psr\Log\LoggerInterface;

class OrderCancel extends Command
{
    const THRESHOLD = "threshold";
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Pool
     */
    private $orderStatusPool;
    /**
     * @var OrderCollection
     */
    private $orderCollection;
    /**
     * @var State
     */
    private $state;
    /**
     * @var DateTime
     */
    private $dateTime;
    /**
     * @var EmailHelper
     */
    private $emailHelper;
    /**
     * @var OrderHelper
     */
    private $orderHelper;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var OrderManagementInterface
     */
    private $orderManagement;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * OrderCancel constructor.
     * @param Config $config
     * @param Pool $orderStatusPool
     * @param OrderCollection $orderCollection
     * @param State $state
     * @param DateTime $dateTime
     * @param CustomerRepositoryInterface $customerRepository
     * @param OrderManagementInterface $orderManagement
     * @param LoggerInterface $logger
     */
    public function __construct(
        Config $config,
        Pool $orderStatusPool,
        OrderCollection $orderCollection,
        State $state,
        DateTime $dateTime,
        CustomerRepositoryInterface $customerRepository,
        OrderManagementInterface $orderManagement,
        LoggerInterface $logger
    ) {
        $this->config = $config;
        $this->orderStatusPool = $orderStatusPool;
        $this->orderCollection = $orderCollection;
        $this->dateTime = $dateTime;
        $this->customerRepository = $customerRepository;
        $this->state = $state;
        $this->objectManager = ObjectManager::getInstance();
        parent::__construct();
        $this->orderManagement = $orderManagement;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName('order:cancel');
        $this->setDescription('Cancels all the orders which are pending after the given threshold time');
        $options = [
            new InputOption(self::THRESHOLD, 't', InputOption::VALUE_OPTIONAL, 'Time in minutes after which the order will be canceled', $this->config->getThresholdTime())
        ];
        $this->setDefinition($options);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND);
        $this->orderHelper = $this->objectManager->get(OrderHelper::class); //Object Manager is used because Helper class object can't be created by Constructor
        $this->emailHelper = $this->objectManager->get(EmailHelper::class); //Object Manager is used because Helper class object can't be created by Constructor
        $threshold = $input->getOption(self::THRESHOLD);
        if (!strlen($threshold)) {
            throw new LocalizedException(__("Threshold value cannot be blank"));
        }
        $symfonyStyle = new SymfonyStyle($input, $output);

        $orderStatus = $this->orderStatusPool->getPendingOrderStatuses();
        $currentTime = strtotime($this->dateTime->date());
        $orders = $this->orderCollection->create()
            ->addAttributeToFilter('status', $orderStatus);

        if ($output->isDebug()) {
            $progressBar = $symfonyStyle->createProgressBar(100);
            $progressBar->setFormat('%current%/%max% [%bar%] <info>%percent:3s%%</info>');
        }

        $count = 0;
        /** @var \Magento\Sales\Api\Data\OrderInterface $order */
        foreach ($orders as $order) {
            $createdTime = strtotime($order->getCreatedAt());
            $interval  = abs($createdTime - $currentTime);
            $minutesDifference  = round($interval / 60);
            if ($minutesDifference >= $threshold) {
                if ($this->orderManagement->cancel($order->getEntityId())) {
                    $this->orderHelper->setStatusAndState($order, 'canceled', "Payment time Elapsed, Order Auto Canceled");
                } else {
                    $this->logger->debug('Order could not be cancelled by command');
                }
                try {
                    $customer = $this->customerRepository->getById($order->getCustomerId());
                } catch (NoSuchEntityException $e) {
                } catch (LocalizedException $e) {
                }
                $this->emailHelper->sendOrderCancelEmail($order, $customer, $order->getCustomerEmail());
                $count++;

                if ($output->isDebug()) {
                    sleep(1);
                    $progressBar->advance();
                }
            }
        }

        if ($output->isDebug()) {
            $progressBar->finish();
        }

        $message = '<info>' . $count . ' Orders Canceled </info>';
        $output->writeln("");
        $output->writeln($message);
    }
}
