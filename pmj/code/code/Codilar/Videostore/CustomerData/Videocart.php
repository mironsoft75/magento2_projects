<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 27/11/18
 * Time: 4:22 PM
 */

namespace Codilar\Videostore\CustomerData;

use Codilar\Videostore\Api\VideostoreCartRepositoryInterface;
use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Framework\UrlInterface;

class Videocart  extends \Magento\Framework\DataObject implements SectionSourceInterface
{

    private $videostoreCart;
    /**
     * @var UrlInterface
     */
    private $url;
    /**
     * Videocart constructor.
     * @param VideostoreCartRepositoryInterface $videostoreCartRepository
     * @param UrlInterface $url
     * @param array $data
     */
    public function __construct(
        VideostoreCartRepositoryInterface $videostoreCartRepository,
        UrlInterface $url,
        array $data = [])
    {
        $this->videostoreCart = $videostoreCartRepository;
        parent::__construct($data);
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function getSectionData()
    {
        $products = $this->videostoreCart->getProducts();

        return [
            'count' => count( $products),
            'products' => $products,
            'proceedUrl' => $this->url->getUrl('videostore/cart/')
        ];
    }
}