<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Meta\Model;


use Codilar\Meta\Api\Data\MetaData\MetaInterface;
use Codilar\Meta\Api\Data\MetaData\MetaInterfaceFactory;
use Codilar\Meta\Api\MetaManagementInterface;

class MetaManagement implements MetaManagementInterface
{
    /**
     * @var MetaInterfaceFactory
     */
    private $metaInterfaceFactory;

    /**
     * MetaManagement constructor.
     * @param MetaInterfaceFactory $metaInterfaceFactory
     */
    public function __construct(
        MetaInterfaceFactory $metaInterfaceFactory
    )
    {
        $this->metaInterfaceFactory = $metaInterfaceFactory;
    }

    /**
     * @param string $name
     * @param string $content
     * @return \Codilar\Meta\Api\Data\MetaData\MetaInterface
     */
    public function getMetaData($name, $content)
    {
        /** @var MetaInterface $metaData */
        $metaData = $this->metaInterfaceFactory->create();
        $content = ($content == null) ? "" : $content;
        return $metaData->setName($name)->setContent($content);
    }
}