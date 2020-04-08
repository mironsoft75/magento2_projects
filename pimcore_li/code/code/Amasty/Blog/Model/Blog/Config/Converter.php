<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\Blog\Config;

use Magento\Framework\Config\ConverterInterface;

class Converter implements ConverterInterface
{
    /**
     * Convert dom node tree to array
     *
     * @param mixed $source
     * @return array
     */
    public function convert($source)
    {
        $config = [];

        $sidebarNodes = $source->getElementsByTagName('sidebar');
        /** @var $sidebarNodes \DOMElement */
        foreach ($sidebarNodes as $sidebarNode) {
            $sidebar = [];
            /** @var $sidebarNode \DOMElement */
            foreach ($sidebarNode->childNodes as $childNode) {
                if ($childNode instanceof \DOMElement  && $childNode->nodeType == XML_ELEMENT_NODE) {
                    $elements = ['label', 'frontend_block', 'backend_image', 'sort_order'];
                    $sidebar[$childNode->tagName] = $this->parseNode($elements, $childNode);
                }
            }
            $config ['sidebar']= $sidebar;
        }

        $sidebarNodes = $source->getElementsByTagName('content');
        /** @var $sidebarNodes \DOMElement */
        foreach ($sidebarNodes as $sidebarNode) {
            $sidebar = [];
            /** @var $sidebarNode \DOMElement */
            foreach ($sidebarNode->childNodes as $childNode) {
                if ($childNode instanceof \DOMElement && $childNode->nodeType == XML_ELEMENT_NODE) {
                    $elements = ['label', 'frontend_block', 'backend_image', 'sort_order'];
                    $sidebar[$childNode->tagName] = $this->parseNode($elements, $childNode);
                }
            }
            $config ['content']= $sidebar;
        }

        $sidebarNodes = $source->getElementsByTagName('color_schemes');
        /** @var $sidebarNodes \DOMElement */
        foreach ($sidebarNodes as $sidebarNode) {
            $sidebar = [];
            /** @var $sidebarNode \DOMElement */
            foreach ($sidebarNode->childNodes as $childNode) {
                if ($childNode instanceof \DOMElement && $childNode->nodeType == XML_ELEMENT_NODE) {
                    $elements = ['label'];
                    $sidebar[$childNode->tagName] = $this->parseNode($elements, $childNode);

                    foreach ($childNode->getElementsByTagName('data') as $item) {
                        $elements = ['textcolor', 'textcolor2', 'hicolor'];
                        $sidebar[$childNode->tagName]['data'] = $this->parseNode($elements, $childNode);
                        $a = $item;
                    }

                }
            }
            $config ['color_schemes']= $sidebar;
        }


        return $config;
    }

    protected function parseNode($elements, $childNode)
    {
        $result = [];
        /** @var $childNode \DOMElement */
        foreach ($elements as $element) {
            $node = $childNode->getElementsByTagName($element)->item(0)->nodeValue;
            $result[$element] = $node;
        }
        return $result;
    }
}
