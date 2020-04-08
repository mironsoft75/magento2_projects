<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Block\Adminhtml\Timeslot\Edit;


use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;
use Codilar\Timeslot\Api\TimeslotRepositoryInterface;
use Codilar\Timeslot\Model\ResourceModel\Timeslot as TimeslotResource;

abstract class GenericButton implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    private $context;
    /**
     * @var TimeslotRepositoryInterface
     */
    private $timeslotRepository;

    /**
     * GenericButton constructor.
     * @param Context $context
     * @param TimeslotRepositoryInterface $timeslotRepository
     */
    public function __construct(
        Context $context,
        TimeslotRepositoryInterface $timeslotRepository)
    {
        $this->context = $context;
        $this->timeslotRepository = $timeslotRepository;
    }

    /**
     * @return int|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getTimeslotId() {
        try {
            $id = $this->context->getRequest()->getParam(TimeslotResource::ID_FIELD);
            return $this->timeslotRepository->load($id)->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    public function getUrl($route = '', $params = []) {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}