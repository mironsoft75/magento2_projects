<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Pwa\Api;

use Codilar\Pwa\Model\Pwa as Model;
use Codilar\Pwa\Model\ResourceModel\Pwa\Collection;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface PwaRepositoryInterface
{
    /**
     * @param string $value
     * @param string $field
     * @return Model
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null);

    /**
     * @return Model
     */
    public function create();

    /**
     * @param Model $model
     * @return Model
     * @throws AlreadyExistsException
     */
    public function save(Model $model);

    /**
     * @param Model $model
     * @return $this
     * @throws LocalizedException
     */
    public function delete(Model $model);

    /**
     * @return Collection
     */
    public function getCollection();
}