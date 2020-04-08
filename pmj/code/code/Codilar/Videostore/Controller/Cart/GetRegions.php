<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 3/12/18
 * Time: 3:59 PM
 */

namespace Codilar\Videostore\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Directory\Model\ResourceModel\Region\Collection as RegionCollection;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Exception\LocalizedException;

class GetRegions extends Action
{
    protected $_pageFactory;
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;
    /**
     * @var RegionCollection
     */
    private $regionCollection;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param JsonFactory $resultJsonFactory
     * @param RegionCollection $regionCollection
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        JsonFactory $resultJsonFactory,
        RegionCollection $regionCollection
    )
    {
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->regionCollection = $regionCollection;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();

        $result = $this->resultJsonFactory->create();

        try{
            $states = $this->regionCollection->addFieldToFilter('country_id',$this->getRequest()->getParam('country'))->toOptionArray();
            $stateList = array();
            foreach ($states as $state){
                if($state['value']){
                    $temp['value'] = $state['value'];
                    $temp['label'] = $state['label'];
                    array_push($stateList, $temp);
                }
            }
        } catch (\Exception $exception){
            throw new LocalizedException(__("Something went wrong while getting the regions ". $exception->getMessage()));
        }
        return $result->setData(['success' => true,'value'=>$stateList]);
    }
}