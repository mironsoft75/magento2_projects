<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block;

class Social extends \Amasty\Blog\Block\Content\Post
{

    /**
     * Social Networks
     *
     * @return array
     */
    public function getButtons()
    {
        return $this->networksModel->getNetworks();
    }

    public function getButtonsCount()
    {
        return count($this->getButtons());
    }

    public function getButtonUrl($button)
    {
        $url = $button->getUrl();

        $url = str_replace("{url}", urlencode($this->getPost()->getPostUrl()), $url);
        $url = str_replace("{title}", urlencode($this->getPost()->getTitle()), $url);
        $url = str_replace("{description}", urlencode($this->getPost()->getMetaDescription()), $url);

        if ($button->getImage()){
            $url = str_replace("{image}", urlencode($this->getPost()->getPostThumbnailSrc()), $url);
        }

        return $url;
    }

    public function getButtonHtml($button)
    {
        if ($key = $button->getValue()){

            $block = $this->getLayout()->createBlock('Amasty\Blog\Block\Social\Button');
            if ($block){
                $block
                    ->setTemplate("Amasty_Blog::social/{$key}.phtml")
                    ->setButton($button)
                    ;
                return $block->toHtml();
            }
        }
        return '';
    }

    public function getHasImage($button)
    {
        return ($button->getImage() && !!$this->getPost()->hasThumbnail()) ||
                !$button->getImage();
    }

}