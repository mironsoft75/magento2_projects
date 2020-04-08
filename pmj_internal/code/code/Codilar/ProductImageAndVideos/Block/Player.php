<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 3/6/19
 * Time: 12:32 PM
 */

namespace Codilar\ProductImageAndVideos\Block;


use Magento\Framework\View\Element\Template;

class Player extends \Magento\Framework\View\Element\Template
{
    protected $videostoreCartRepository;
    public function __construct(
        Template\Context $context,
        \Codilar\Videostore\Model\VideostoreCartRepository  $videostoreCartRepository,
        array $data = []
    )
    {
        $this->videostoreCartRepository=$videostoreCartRepository;
        parent::__construct($context, $data);
    }
    public function getCustomerProductDetails(){
        return $this->videostoreCartRepository->getProducts();
    }
}