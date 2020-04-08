<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/11/18
 * Time: 2:44 PM
 */
namespace Codilar\StoneAndMetalRates\Block\Adminhtml;
use Magento\Backend\Block\Template;

class Activity extends Template
{
    /**
     * @var \Codilar\StoneAndMetalRates\Model\Activity
     */
    protected $activity;

    public function __construct(
        Template\Context $context,
    \Codilar\StoneAndMetalRates\Model\Activity $activity
)
    {
        $this->activity=$activity;
        parent::__construct($context);
    }
    public function getActivityCollection()
    {
        $data=$this->activity->getCollection()->setOrder('entity_id','DESC');
        return $data;
    }

}