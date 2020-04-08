<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\MegaMenu\Controller\Adminhtml\Links;


use Codilar\MegaMenu\Model\Links;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;

class Update extends Action
{
    /**
     * @var Links
     */
    private $links;

    /**
     * Update constructor.
     * @param Action\Context $context
     * @param Links $links
     */
    public function __construct(
        Action\Context $context,
        Links $links
    )
    {
        parent::__construct($context);
        $this->links = $links;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost();
        $this->links->setUsedLinks($data['usedLinks']);
        $this->links->setUnusedLinks($data['unusedLinks']);
        $this->links->commit();
    }
}