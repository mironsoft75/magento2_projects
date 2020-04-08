<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/11/18
 * Time: 6:06 PM
 */

namespace Codilar\StoneAndMetalRates\Observer;

use Magento\Framework\Event\ObserverInterface;
use Codilar\StoneAndMetalRates\Model\ActivityFactory;
use Codilar\StoneAndMetalRates\Model\ResourceModel\Activity as ActivityResource;
use Codilar\StoneAndMetalRates\Model\StoneAndMetalRatesFactory;
use Codilar\StoneAndMetalRates\Model\ResourceModel\StoneAndMetalRates as StoneAndMetalRatesResource;


class Activity implements ObserverInterface
{
    /**
     * @var ActivityFactory
     */
    protected $activityFactory;
    /**
     * @var ActivityResource
     */
    protected $activityResource;


    public function __construct(

        ActivityFactory $activityFactory,
        ActivityResource $activityResource,
        StoneAndMetalRatesFactory $StoneAndMetalRatesFactory,
        StoneAndMetalRatesResource $StoneAndMetalRatesResource,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date

    )
    {
        $this->activityFactory = $activityFactory;
        $this->activityResource = $activityResource;
        $this->StoneAndMetalRatesFactory = $StoneAndMetalRatesFactory;
        $this->StoneAndMetalRatesResource = $StoneAndMetalRatesResource;
        $this->authSession = $authSession;
        $this->_request = $request;
        $this->_date = $date;

    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $newData = $observer->getData('newData');
        $oldData = $observer->getData('oldData');
        if ($oldData) {
            unset($newData['form_key']);
            unset($oldData['form_key']);
            $diff = array_diff($newData, $oldData);
            $date = $this->_date->date()->format('Y-m-d H:i:s');
            $user = $this->authSession->getUser();
            $userName = $user->getUsername();
            $userId = $user->getUserId();
            $activity = $this->activityFactory->create();
            $data['user_id'] = $userId;
            $data['user_name'] = $userName;
            $data['created_at'] = $date;
            $data['data_id'] = $newData['entity_id'];
            $data['activity'] = json_encode($diff);
            $data['new_data'] = json_encode($newData);
            $data['old_data'] = json_encode($oldData);
            $activity->setData($data);
            $this->activityResource->save($activity);

        }


    }


}