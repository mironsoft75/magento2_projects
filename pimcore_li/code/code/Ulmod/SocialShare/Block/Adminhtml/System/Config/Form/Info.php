<?php
/**
 * SocialShare
 *
 * @package     Ulmod_SocialShare
 * @author      Ulmod <support@ulmod.com>
 * @copyright   Copyright (c) 2016 Ulmod (http://www.ulmod.com/)
 * @license     http://www.ulmod.com/license-agreement.html
 */

namespace Ulmod\SocialShare\Block\Adminhtml\System\Config\Form;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
        
/**
 * Admin config info block
 */
class Info extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var ;ModuleListInterface
     */
    protected $moduleList;

    /**
     * @param ModuleListInterface $moduleList
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        ModuleListInterface $moduleList,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->moduleList = $moduleList;
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $m = $this->moduleList->getOne($this->getModuleName());
        $version  = $m['setup_version'];
        $socialshareimgpath = 'http://www.ulmod.com/pub/media/ulmod_info/um-social-share.png';
        $html = <<<HTML
        <div style="background:url('$socialshareimgpath') no-repeat scroll 15px 11px #f8f8f8;
		border:1px solid #ccc; margin:5px 0; padding:15px 15px 15px 130px;
		background-size: 100px 100px;">
          <p>
		   <strong class="um-product-info">
             <span class="um-prod-name">Social Share for Magento 2</span> 
		   </strong>
          </p>
          <p>
           Take your store to the next level with social share which increase traffic 
		    in your store and grow revenue. publish 
		    any customer’s purchase on every social channels with buyer’s appreciation.
          </p>		  
          <p>
   		     If you have any questions, email us at <a href="mailto:support@ulmod.com">support@ulmod.com</a>.
           </p>
        </div>
HTML;
        return $html;
    }
}
