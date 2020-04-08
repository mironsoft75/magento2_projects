<?php

namespace Pimcore\Ymm\Block;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Pimcore\Ymm\Api\YmmApiManagementInterface;

class Ymm extends Template
{
    CONST YEAR_COL = 'year_id';
    CONST MAKE_COL = 'make_name';
    CONST MODEL_COL = 'model_name';
    /**
     * @var YmmApiManagementInterface
     */
    private $_ymmApiManagement;
    /**
     * @var Session
     */
    private $session;


    public function __construct(
        Template\Context $context,
        YmmApiManagementInterface $ymmApiManagement,
        Session $session,
        array $data = []
    )
    {
        $this->_ymmApiManagement = $ymmApiManagement;
        parent::__construct($context, $data);
        $this->session = $session;
        $this->updateYmmSelectorsInSession();
    }

    public function getYears()
    {
        return $this->_ymmApiManagement->getYearIds();
    }

    public function getMakeNames()
    {
        return $this->_ymmApiManagement->getMakeNames();
    }

    public function getMakesByYear($year)
    {
        return $this->_ymmApiManagement->getMakesByYear($year);
    }

    public function getModelNames($year, $make)
    {
        if (!$year && !$make) return;
        $modelNames = $this->_ymmApiManagement->getModelByYearAndMake($year, $make);
        return \Zend_Json::decode($modelNames);
    }

    public function getSelectedYear()
    {
        return $this->session->getData(self::YEAR_COL) ?? NULL;
    }

    public function getSelectedMake()
    {
        return $this->session->getData(self::MAKE_COL) ?? NULL;
    }

    public function getSelectedModel()
    {
        return $this->session->getData(self::MODEL_COL) ?? NULL;
    }

    private function updateYmmSelectorsInSession()
    {
        $makePageCms = $this->getRequest()->getParam('listinghead_cms_id');
        $makeName = $this->getRequest()->getParam('make_name') ?? $this->session->getData(self::MAKE_COL);
        $modelName = $this->getRequest()->getParam('model_name') ?? $this->session->getData(self::MODEL_COL);
        $yearId = $this->getRequest()->getParam('year_id') ?? $this->session->getData(self::YEAR_COL);
        if (isset($makePageCms)) {
            $this->session->setData(self::YEAR_COL, NULL);
            $this->session->setData(self::MODEL_COL, NULL);
            $this->session->setData(self::MAKE_COL, $makeName);
        } else {
            $this->session->setData(self::YEAR_COL, $yearId);
            $this->session->setData(self::MODEL_COL, $modelName);
            $this->session->setData(self::MAKE_COL, $makeName);
        }
    }
}
