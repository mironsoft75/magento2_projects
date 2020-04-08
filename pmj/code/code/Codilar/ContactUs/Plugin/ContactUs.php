<?php

namespace Codilar\ContactUs\Plugin;

use Codilar\ContactUs\Model\ContactUsRepository;
use Psr\Log\LoggerInterface;

/**
 * Class ContactUs
 * @package Codilar\ContactUs\Plugin
 */
class ContactUs
{
    /**
     * @var ContactUsRepository
     */
    protected $_contactUsRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ContactUs constructor.
     * @param ContactUsRepository $contactUsRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        ContactUsRepository $contactUsRepository,
        LoggerInterface $logger
    )
    {
        $this->_contactUsRepository = $contactUsRepository;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Contact\Controller\Index\Post $subject
     * @param $result
     * @return mixed
     */
    public function afterExecute(\Magento\Contact\Controller\Index\Post $subject, $result)
    {
        $params = $subject->getRequest()->getParams();
        $contactUs = $this->_contactUsRepository->create();
        $contactUs->setData([
            "name" => $params['name'],
            "email" => $params['email'],
            "phone_number" => $params['telephone'],
            "message" => $params['comment']
        ]);
        try{
            $this->_contactUsRepository->save($contactUs);
        }
        catch (\Exception $e){
            $this->logger->info($e->getMessage());
        }
        return $result;
    }
}