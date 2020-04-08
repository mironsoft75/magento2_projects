<?php

namespace Codilar\Pwa\Block;


use Magento\Backend\Block\Template;

class CsvImporter extends Template
{
    protected $_template = "Codilar_Pwa::importer/csv_importer.phtml";

    /**
     * @return string
     */
    public function getPostUrl() {
        return $this->getUrl('*/*/post');
    }
}