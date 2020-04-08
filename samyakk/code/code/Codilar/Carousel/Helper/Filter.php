<?php
/**
 * @package     magento 2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Helper;


use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\App\Area;
use Magento\Framework\App\AreaList;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;

class Filter
{

    const TYPE_PAGE = "page";
    const TYPE_BLOCK = "block";

    /**
     * @var AreaList
     */
    private $areaList;
    /**
     * @var FilterProvider
     */
    private $filterProvider;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var State
     */
    private $state;

    /**
     * Filter constructor.
     * @param AreaList $areaList
     * @param FilterProvider $filterProvider
     * @param StoreManagerInterface $storeManager
     * @param State $state
     */
    public function __construct(
        AreaList $areaList,
        FilterProvider $filterProvider,
        StoreManagerInterface $storeManager,
        State $state
    )
    {
        $this->areaList = $areaList;
        $this->filterProvider = $filterProvider;
        $this->storeManager = $storeManager;
        $this->state = $state;
    }

    /**
     * @param string $content
     * @param string $type
     * @return string
     */
    public function filterStaticContent($content, $type = self::TYPE_BLOCK)
    {
        try {
            $emulatedResult = $this->state->emulateAreaCode(
                Area::AREA_FRONTEND,
                [$this, '_makeFilter'],
                [$content, $type]
            );
        } catch (\Exception $exception) {
            $emulatedResult = $content;
        }
        return $emulatedResult;
    }

    /**
     * @param $content
     * @param $type
     * @return mixed
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function _makeFilter($content, $type)
    {
        $this->areaList->getArea(Area::AREA_FRONTEND)->load(Area::PART_DESIGN);
        if ($type === self::TYPE_BLOCK) {
            $filter = $this->filterProvider->getBlockFilter();
        } else if ($type === self::TYPE_PAGE) {
            $filter = $this->filterProvider->getPageFilter();
        } else {
            throw new LocalizedException(__("Type %1 not found", $type));
        }
        return $filter->setStoreId($this->storeManager->getStore()->getId())->filter($content);
    }
}