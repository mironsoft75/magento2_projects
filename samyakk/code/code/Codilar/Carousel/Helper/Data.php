<?php
/**
 * @package     magento 2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Helper;

use Codilar\Carousel\Api\CarouselItemRepositoryInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{
    /**
     * @var CarouselItemRepositoryInterface
     */
    private $carouselItemRepository;

    /**
     * Data constructor.
     * @param Context $context
     * @param CarouselItemRepositoryInterface $carouselItemRepository
     */
    public function __construct(
        Context $context,
        CarouselItemRepositoryInterface $carouselItemRepository
    )
    {
        parent::__construct($context);
        $this->carouselItemRepository = $carouselItemRepository;
    }

    /**
     * @param $itemId
     * @return array|string|null
     */
    public function getLinkData($itemId)
    {
        $carouselItem = $this->carouselItemRepository->load($itemId);
        $link = $carouselItem->getLink();
        $identifier = $link->getIdentifier();
        $identifier = explode(",", $identifier);
        return $identifier;
    }
}