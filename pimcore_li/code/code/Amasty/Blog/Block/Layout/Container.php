<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Layout;

class Container extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Amasty\Blog\Helper\Data\Layout
     */
    private $helperLayout;

    /**
     * Container constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Amasty\Blog\Helper\Data\Layout $helperLayout
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Amasty\Blog\Helper\Data\Layout $helperLayout,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helperLayout = $helperLayout;
    }

    /**
     * Set Type
     * sidebar/content
     *
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {

        $blocks = $this->helperLayout->getBlocks($type);

        if ($blocks && is_array($blocks) && count($blocks)){

            foreach ($blocks as $data){

                $object = new \Magento\Framework\DataObject($data);

                if ($object->getFrontendBlock()){

                    $alias = $object->getValue();
                    $name = "am.blog.{$type}.{$alias}";
                    $block = $this
                        ->getLayout()
                        ->createBlock(
                            $object->getFrontendBlock(),
                            $name
                        );

                    if ($block){
                        $this->append($block, $alias);

                    }
                }
            }
        }

        return $this;
    }
}