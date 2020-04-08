<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 26/11/18
 * Time: 12:55 PM
 */

namespace Codilar\Videostore\Api;

use Codilar\Videostore\Api\Data\VideostoreRequestActivityInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface VideostoreRequestActivityRepositoryInterface
{
    /**
     * @param string $value
     * @param string $field
     * @return VideostoreRequestActivityInterface
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null);

    /**
     * @return VideostoreRequestActivityInterface
     */
    public function create();

    /**
     * @param VideostoreRequestActivityInterface $model
     * @return VideostoreRequestActivityInterface
     * @throws AlreadyExistsException
     */
    public function save(VideostoreRequestActivityInterface $model);

    /**
     * @param VideostoreRequestActivityInterface $model
     * @return $this
     * @throws LocalizedException
     */
    public function delete(VideostoreRequestActivityInterface $model);

    /**
     * @return \Codilar\Videostore\Model\ResourceModel\VideostoreRequestActivity\Collection
     */
    public function getCollection();
}