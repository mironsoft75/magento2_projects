<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbySeo
 */


namespace Amasty\ShopbySeo\Plugin\Adminhtml;

class ConfigPlugin
{
    const AMASTY_SHOPBY_SEO = 'amasty_shopby_seo';

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    /**
     * @var \Amasty\ShopbySeo\Model\Source\OptionSeparator
     */
    private $optionSeparator;

    /**
     * @var \Magento\Framework\Filter\FilterManager
     */
    private $filter;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Amasty\ShopbySeo\Model\Source\OptionSeparator $optionSeparator,
        \Magento\Framework\Filter\FilterManager $filter
    ) {
        $this->messageManager = $messageManager;
        $this->optionSeparator = $optionSeparator;
        $this->filter = $filter;
    }

    /**
     * @param $subject
     * @return mixed
     */
    public function beforeSave($subject)
    {
        $groups = $subject->getGroups();
        if ($subject->getSection() !== self::AMASTY_SHOPBY_SEO) {
            return $groups;
        }

        $fields = isset($groups['url']) ? $groups['url']['fields'] : [];
        $resultSpecialChar = $fields['special_char']['value'];
        $message = '';

        if ($fields) {
            if ($fields['special_char']['value'] == $fields['option_separator']['value']) {
                $message = __('"Replace Special Characters With" setting value was changed 
            because it cannot be the same as the value of "Separate Attribute Options With" setting.');
                $specialChars = $this->optionSeparator->toArray();
                unset($specialChars[$fields['special_char']['value']]);
                $resultSpecialChar = array_shift($specialChars);
            }

            if ($fields['special_char']['value'] == '--' && $fields['option_separator']['value'] == '-') {
                $message = __('"Replace Special Characters With" setting value was changed 
            because it cannot be "--" when the value of "Separate Attribute Options With" setting is "-".');
                $resultSpecialChar = '_';
            }
        }
        $groups['url']['fields']['filter_word']['value'] = str_replace(
            '-',
            $resultSpecialChar,
            $this->filter->translitUrl($groups['url']['fields']['filter_word']['value'])
        );
        if ($message) {
            $groups['url']['fields']['special_char']['value'] = $resultSpecialChar;
            $this->messageManager->addWarningMessage($message);
        }
        $subject->setGroups($groups);

        return $groups;
    }
}