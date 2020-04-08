<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Config\Source;

use Magento\Email\Model\Template\Config as EmailConfig;
use Magento\Email\Model\ResourceModel\Template\CollectionFactory as CustomEmailTemplateCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class EmailTemplate implements OptionSourceInterface
{
    const OPTION_NO_EMAIL = "_codilar_option_no_email";
    /**
     * @var EmailConfig
     */
    private $emailConfig;
    /**
     * @var CustomEmailTemplateCollectionFactory
     */
    private $customEmailTemplateCollectionFactory;

    /**
     * EmailTemplate constructor.
     * @param EmailConfig $emailConfig
     * @param CustomEmailTemplateCollectionFactory $customEmailTemplateCollectionFactory
     */
    public function __construct(
        EmailConfig $emailConfig,
        CustomEmailTemplateCollectionFactory $customEmailTemplateCollectionFactory
    )
    {
        $this->emailConfig = $emailConfig;
        $this->customEmailTemplateCollectionFactory = $customEmailTemplateCollectionFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $response = array_merge(
            $this->customEmailTemplateCollectionFactory->create()->toOptionArray(),
            $this->emailConfig->getAvailableTemplates()
        );
        usort($response, [$this, 'compareByName']);
        array_unshift($response, [
            'label' =>  __("-- NO EMAIL --"),
            'value' =>  self::OPTION_NO_EMAIL
        ]);
        return $response;
    }

    private function compareByName($a, $b)
    {
        return strcmp($a["label"], $b["label"]);
    }
}