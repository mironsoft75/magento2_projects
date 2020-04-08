<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Shipping\Model\Config\Source;


use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Categories implements OptionSourceInterface
{
    /**
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * Categories constructor.
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory
    )
    {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $response = [];
        /** @var \Magento\Catalog\Model\Category $category */
        foreach ($this->getCategories() as $category) {
            $response[] = [
                'label' =>  sprintf("%s (%s)", $category->getName(), $category->getProductCount()),
                'value' =>  $category->getId()
            ];
        }
        return $response;
    }

    protected function getCategories()
    {
        return $this->categoryCollectionFactory->create()->addNameToResult();
    }
}