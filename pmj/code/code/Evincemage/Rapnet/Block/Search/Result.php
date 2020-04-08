<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Evincemage\Rapnet\Block\Search;

use Magento\Framework\View\Element\Template\Context;
use Evincemage\Rapnet\Helper\Data as Helper;
use Magento\Framework\Session\SessionManagerInterface;

class Result extends AbstractProduct
{
    /**
     * Result constructor.
     * @param Context $context
     * @param SessionManagerInterface $sessionManager
     * @param Helper $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        SessionManagerInterface $sessionManager,
        Helper $helper,
        array $data = []
    )
    {
        parent::__construct($context, $sessionManager, $helper,$data);
    }

}