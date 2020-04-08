<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Api;

use Codilar\Carousel\Model\Carousel\Item as Model;
use Codilar\Carousel\Model\ResourceModel\Carousel\Item\Collection;

interface CarouselItemRepositoryInterface
{
    /**
     * @param string$value
     * @param null|string $field
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function load($value, $field = null);

    /**
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function create();

    /**
     * @param Model|\Codilar\Carousel\Api\Data\CarouselItemsInterface $model
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface
     */
    public function save(Model $model);

    /**
     * @param Model|\Codilar\Carousel\Api\Data\CarouselItemsInterface $model
     * @return mixed
     */
    public function delete(Model $model);

    /**
     * @return Collection
     */
    public function getCollection();
}