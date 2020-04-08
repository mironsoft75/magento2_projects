<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Model\Resolver;

use Codilar\BannerSlider\Model\Resolver\DataProvider\Banners;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class BannerSlider implements ResolverInterface
{
    /**
     * @var Banners
     */
    private $bannersDataProvider;

    /**
     * BannerSlider constructor.
     * @param Banners $bannersDataProvider
     */
    public function __construct(
        Banners $bannersDataProvider
    )
    {
        $this->bannersDataProvider = $bannersDataProvider;
    }

    /**
     * Fetches the data from persistence models and format it according to the GraphQL schema.
     *
     * @param \Magento\Framework\GraphQl\Config\Element\Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @throws \Exception
     * @return array
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    )
    {
        $data = $this->bannersDataProvider->getData();
        return $data;
    }
}
