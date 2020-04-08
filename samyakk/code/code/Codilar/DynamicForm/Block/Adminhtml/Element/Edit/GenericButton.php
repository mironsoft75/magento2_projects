<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Block\Adminhtml\Element\Edit;

use Codilar\DynamicForm\Api\Form\ElementRepositoryInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    /**
     * @var Context
     */
    protected $context;
    /**
     * @var ElementRepositoryInterface
     */
    private $elementRepository;

    /**
     * @param Context $context
     * @param ElementRepositoryInterface $elementRepository
     */
    public function __construct(
        Context $context,
        ElementRepositoryInterface $elementRepository
    ) {
        $this->context = $context;
        $this->elementRepository = $elementRepository;
    }

    /**
     * Return Form Element ID
     *
     * @return int|null
     */
    public function getFormElementId()
    {
        try {
            return $this->elementRepository->load(
                $this->context->getRequest()->getParam('id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}