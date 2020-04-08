<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbySeo
 */


use Amasty\ShopbySeo\Helper\Meta;
use Amasty\ShopbySeo\Test\Unit\Traits;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class Meta
 *
 * @see Meta
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @codingStandardsIgnoreFile
 */
class MetaTest extends \PHPUnit\Framework\TestCase
{
    use Traits\ObjectManagerTrait;
    use Traits\ReflectionTrait;

    const REQUEST_VAR = 'request_var';

    const TEST_VALUES = 'test1,test2';

    const TEST_TAG_VALUE = true;

    /**
     * @var Meta|MockObject
     */
    private $meta;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * @var \Amasty\ShopbyBase\Model\FilterSetting
     */
    private $filterSetting;

    /**
     * @var \Magento\Catalog\Model\Layer\Filter\FilterInterface|MockObject
     */
    private $filter;

    public function setUp()
    {
        $this->meta = $this->getMockBuilder(Meta::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMockForAbstractClass();

        $this->request = $this->getObjectManager()
            ->getObject(\Magento\Framework\App\Request\Http::class);

        $this->filterSetting = $this->getObjectManager()
            ->getObject(\Amasty\ShopbyBase\Model\FilterSetting::class);

        $this->filter = $this->createMock(\Magento\Catalog\Model\Layer\Filter\FilterInterface::class, []);

        $this->setProperty($this->meta, '_request', $this->request, Meta::class);
    }

    /**
     * @covers Meta::getTagByData
     * @dataProvider getTagByDataDataProvider
     */
    public function testGetTagByData($tagKey, $settingMode, $tagValue, $expected)
    {
        $this->filter->expects($this->any())->method('getRequestVar')
            ->willReturn(self::REQUEST_VAR);
        $this->request->setParam(self::REQUEST_VAR, self::TEST_VALUES);
        $this->filterSetting->setData($tagKey, $settingMode);
        $data = [
            'setting' => $this->filterSetting,
            'filter' => $this->filter
        ];

        $result = $this->meta->getTagByData($tagKey, $tagValue, $data);
        $this->assertEquals($expected, $result);
    }

    public function getTagByDataDataProvider()
    {
        return [
            ['index_mode', 2, self::TEST_TAG_VALUE, self::TEST_TAG_VALUE],
            ['index_mode', 1, self::TEST_TAG_VALUE, false ],
            ['index_mode', 0, self::TEST_TAG_VALUE, false],
            ['follow_mode', 2, self::TEST_TAG_VALUE, self::TEST_TAG_VALUE]
        ];
    }
}
