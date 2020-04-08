<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\Comment;

use Amasty\Blog\Helper\Settings;
use Magento\Email\Model\Template;

class Notification extends \Magento\Framework\Model\AbstractModel
{
    const STATUS_WAIT = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_CANCELED = 3;
    const STATUS_FAILED = 4;

    protected $_subscription;
    protected $_comment;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManagerInterface;
    /**
     * @var Settings
     */
    private $settingsHelper;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManagerInterface;
    /**
     * @var Template
     */
    private $templateModel;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Amasty\Blog\Helper\Settings $settingsHelper,
        \Magento\Email\Model\Template $templateModel,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \Magento\Framework\ObjectManagerInterface $objectManagerInterface,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->objectManagerInterface = $objectManagerInterface;
        $this->settingsHelper = $settingsHelper;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->templateModel = $templateModel;
    }

    public function _construct()
    {
        parent::_construct();
        $this->_init('mpblog/comment_notification');
    }

    public function getOptionsArray()
    {
        return array(
            self::STATUS_WAIT => __("Wait"),
            self::STATUS_SUCCESS => __("Sent"),
            self::STATUS_CANCELED => __("Canceled"),
            self::STATUS_FAILED => __("Failed"),
        );
    }

    public function getSubscription()
    {
        if (!$this->_subscription){
            $this->_subscription = $this->objectManagerInterface
                ->create('Amasty\Blog\Model\Comments\Subscriptions')
                ->load($this->getSubscriptionId());
        }
        return $this->_subscription;
    }

    public function getComment()
    {
        if (!$this->_comment){
            $this->_comment = $this->objectManagerInterface
                ->create('Amasty\Blog\Model\Comments')
                ->load($this->getCommentId());
        }
        return $this->_comment;
    }

    public function send($testEmail = false)
    {
        if ($this->settingsHelper->getCommentNotificationsEnabled() || !!$testEmail){

            $data = array();
            $data['post'] = $this->getComment()->getPost();
            $data['comment'] = $this->getComment();
            $data['subscription'] = $this->getSubscription();

            $storeId = $this->getStoreId();
            $data['store'] = $this->storeManagerInterface->getStore($storeId);


            $receiver = $testEmail ? $testEmail : $this->getSubscription()->getEmail();

            if (trim($receiver)){
                
                $mailTemplate = $this->templateModel;
                try {
                    //add save
                    $mailTemplate
                        ->setDesignConfig(array('area' => 'frontend', 'store'=>$storeId))
                        ->send(
                        1,
                        1,
                        trim($receiver),
                        $this->getSubscription()->getCustomerName(),
                        $data,
                        $storeId
                    )
                    ;

                    if (!$testEmail){
                        $this->setStatus(self::STATUS_SUCCESS)->save();
                    }


                } catch (\Exception $e) {
                    if (!$testEmail){
                        $this->setStatus(self::STATUS_FAILED)->save();
                    }

                }
            }
        }
        return $this;
    }

    public function canCancel()
    {
        return $this->getStatus() == self::STATUS_WAIT;
    }

    public function cancel()
    {
        if ($this->canCancel()){

            $this
                ->setStatus(self::STATUS_CANCELED)
                ->save()
            ;
        } else {


        }

        return $this;
    }

}