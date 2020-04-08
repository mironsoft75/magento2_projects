<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 28/11/18
 * Time: 7:47 PM
 */

namespace Codilar\Videostore\Observer;


use Codilar\Videostore\Api\VideostoreCartRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Session\SessionManager;

class CustomerLogin implements ObserverInterface
{
    /**
     * @var VideostoreCartRepositoryInterface
     */
    private $videostoreCartRepository;
    /**
     * @var SessionManager
     */
    private $sessionManager;

    /**
     * CustomerLogin constructor.
     * @param VideostoreCartRepositoryInterface $videostoreCartRepository
     * @param SessionManager $sessionManager
     */
    public function __construct(
        VideostoreCartRepositoryInterface $videostoreCartRepository,
        SessionManager $sessionManager
    )
    {
        $this->videostoreCartRepository = $videostoreCartRepository;
        $this->sessionManager = $sessionManager;
    }

    /**
     * @param Observer $observer
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        try{
            $sessionId = $this->sessionManager->getSessionId();
            $products = $this->videostoreCartRepository->getCollection()
                ->addFieldToFilter('videostore_customer_session_id', $sessionId);
            foreach ($products as $product){
                $product->setVideostoreCustomerId($customer->getId());
                $product->save();
         }
        }
        catch (\Exception $exception){
            throw new LocalizedException(__($exception->getMessage()));
        }
    }

}