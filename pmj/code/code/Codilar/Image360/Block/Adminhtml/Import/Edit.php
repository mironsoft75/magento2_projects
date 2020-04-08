<?php

namespace Codilar\Image360\Block\Adminhtml\Import;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'object_id';
        $this->_controller = 'adminhtml_import';
        $this->_blockGroup = 'Codilar_Image360';
        $this->_headerText = 'Import Magic 360 images';

        parent::_construct();

        $this->_formScripts[] = '
            require([\'image360\'], function(image360){
                image360.initAdvancedRadios();
            });
        ';

        $this->removeButton('back');
        $this->removeButton('reset');
        $this->updateButton('save', 'label', __('Import Images'));
        $this->updateButton('save', 'region', 'footer');
    }
}
