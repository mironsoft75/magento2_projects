<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Core\Model\TemplateParser;


interface FilterPipelineInterface
{
    /**
     * @param array $data
     * @return $this
     */
    public function setData($data);

    /**
     * @param string $template
     * @return string
     */
    public function filter($template);
}