<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Block\Adminhtml\Form\Edit;

use Magento\Backend\Block\Widget\Context;
use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var FormRepositoryInterface
     */
    protected $formRepository;

    /**
     * @param Context $context
     * @param FormRepositoryInterface $formRepository
     */
    public function __construct(
        Context $context,
        FormRepositoryInterface $formRepository
    ) {
        $this->context = $context;
        $this->formRepository = $formRepository;
    }

    /**
     * Return Form ID
     *
     * @return int|null
     */
    public function getFormId()
    {
        try {
            return $this->formRepository->load(
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