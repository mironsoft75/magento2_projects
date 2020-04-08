<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\MegaMenu\Block\Adminhtml;


use Codilar\MegaMenu\Api\CategoryRepositoryInterface;
use Codilar\MegaMenu\Model\Links as LinksModel;
use Magento\Backend\Block\Template;
use Magento\Framework\Webapi\ServiceOutputProcessor;

class Links extends Template
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
    /**
     * @var ServiceOutputProcessor
     */
    private $outputProcessor;
    /**
     * @var LinksModel
     */
    private $linksModel;

    /**
     * Links constructor.
     * @param Template\Context $context
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ServiceOutputProcessor $outputProcessor
     * @param LinksModel $linksModel
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CategoryRepositoryInterface $categoryRepository,
        ServiceOutputProcessor $outputProcessor,
        LinksModel $linksModel,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->categoryRepository = $categoryRepository;
        $this->outputProcessor = $outputProcessor;
        $this->linksModel = $linksModel;
    }

    public function getUsedCategories()
    {
        return $this->outputProcessor->convertValue($this->categoryRepository->getMenuData(), \Codilar\MegaMenu\Api\Data\CategoryInterface::class . '[]');
    }

    public function getUnusedCategories()
    {
        return $this->linksModel->getUnusedLinks();
    }
}