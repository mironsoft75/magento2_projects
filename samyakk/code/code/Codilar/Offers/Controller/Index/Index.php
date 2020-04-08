<?php
namespace Codilar\Offers\Controller\Index;
class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;        
        parent::__construct($context);
    }
    
    public function execute()
    {
        try {
            echo '
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css" />
<script src="http://127.0.0.1/eat/pub/static/version1541159770/frontend/Codilar/eat/en_US/jquery.js"></script>
<script src="http://127.0.0.1/eat/pub/static/version1541159770/frontend/Codilar/eat/en_US/Codilar_Offers/js/owl_carousel.js"></script>
<div class="owl-carousel owl-theme">
    <div class="item"><h4>hello</h4></div>
    <div class="item"><h4>hello2</h4></div>
    <div class="item"><h4>hello3</h4></div>
    <div class="item"><h4>hello4</h4></div>
    <div class="item"><h4>hello5</h4></div>
    <div class="item"><h4>hello6</h4></div>
    <div class="item"><h4>hello7</h4></div>
    <div class="item"><h4>hello8</h4></div>
    <div class="item"><h4>hello9</h4></div>
    <div class="item"><h4>hello10</h4></div>
    <div class="item"><h4>hello11</h4></div>
    <div class="item"><h4>hello12</h4></div>
</div>

<script>

         $(\'.owl-carousel\').owlCarousel({
             loop:true,
             margin:0
         });
</script>';die;
            echo $this->_view->getLayout()->createBlock("Codilar\Offers\Block\HomepageBlocks")->toHtml();
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $e->getTraceAsString();
            die;
        }

        return $this->resultPageFactory->create();  
    }
}
