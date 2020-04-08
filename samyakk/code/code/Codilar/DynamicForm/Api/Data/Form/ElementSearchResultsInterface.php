<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Api\Data\Form;


use Magento\Framework\Api\SearchResultsInterface;

interface ElementSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items list.
     *
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementInterface[]
     */
    public function getItems();

    /**
     * Set items list.
     *
     * @param \Codilar\DynamicForm\Api\Data\Form\ElementInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}