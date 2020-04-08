<?php
/**
 * Created by PhpStorm.
 * User: pimcore
 * Date: 29/12/18
 * Time: 1:45 AM
 */

namespace Pimcore\CartOption\Plugin;

class SelectAll
{
    /**
     * @param \Magento\Backend\Block\Widget\Grid\Massaction\AbstractMassaction $subject
     * @return string
     */
    public function afterGetGridIdsJson(\Magento\Backend\Block\Widget\Grid\Massaction\AbstractMassaction $subject)
    {
        if (!$subject->getUseSelectAll()) {
            return '';
        }
        /** @var \Magento\Framework\Data\Collection $allIdsCollection */
        $allIdsCollection = clone $subject->getParentBlock()->getCollection();
        $gridIds = $allIdsCollection->getAllIds();

        if (!empty($gridIds)) {
            return join(",", $gridIds);
        }
        return '';
    }
}