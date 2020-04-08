<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\Router;

/** Storage for Route Action Data */
class Action extends \Magento\Framework\DataObject
{
    protected $_isRedirect = false;
    protected $_redirectFlag;
    protected $_moduleName;
    protected $_controllerName;
    protected $_actionName;
    protected $_alias;
    protected $_result = false;
    protected $_params = array();


    /**
     * @param $alias
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->_alias = $alias;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->_alias;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    public function setParam($key, $value)
    {
        $this->_params[$key] = $value;
        return $this;
    }

    /**
     * @param $actionName
     * @return $this
     */
    public function setActionName($actionName)
    {
        $this->_actionName = $actionName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActionName()
    {
        return $this->_actionName;
    }

    /**
     * @param $controllerName
     * @return $this
     */
    public function setControllerName($controllerName)
    {
        $this->_controllerName = $controllerName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getControllerName()
    {
        return $this->_controllerName;
    }

    /**
     * @param $isRedirect
     * @return $this
     */
    public function setIsRedirect($isRedirect)
    {
        $this->_isRedirect = $isRedirect;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsRedirect()
    {
        return $this->_isRedirect;
    }

    /**
     * @param $moduleName
     * @return $this
     */
    public function setModuleName($moduleName)
    {
        $this->_moduleName = $moduleName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModuleName()
    {
        return $this->_moduleName;
    }

    /**
     * @param $result
     * @return $this
     */
    public function setResult($result)
    {
        $this->_result = $result;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * @param $redirectFlag
     * @return $this
     */
    public function setRedirectFlag($redirectFlag)
    {
        $this->_redirectFlag = $redirectFlag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRedirectFlag()
    {
        return $this->_redirectFlag;
    }
}