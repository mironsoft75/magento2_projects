<?php

declare(strict_types=1);

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\MegaMenu\Model\Resolver;

use Codilar\MegaMenu\Model\Resolver\DataProvider\CategoryData;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;

use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class Category implements ResolverInterface
{
    /**
     * @var CategoryData
     */
    private $categoryData;

    /**
     * Category constructor.
     * @param CategoryData $categoryData
     */
    public function __construct(
        CategoryData $categoryData
    )
    {
        $this->categoryData = $categoryData;
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
     * @return mixed|Value
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    )
    {
        $categories = $this->getCategories();
        return $categories;
    }

    /**
     * @return array
     */
    protected function getCategories(): array
    {
        return $this->categoryData->getData();
    }
}