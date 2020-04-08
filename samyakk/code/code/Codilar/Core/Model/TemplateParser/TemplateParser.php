<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Core\Model\TemplateParser;

class TemplateParser
{
    /**
     * @var Context
     */
    private $context;

    /**
     * TemplateParser constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    )
    {
        $this->context = $context;
    }

    /**
     * @param string $template
     * @param array $data
     * @return mixed
     */
    public function parse($template, $data = [])
    {
        $data = $this->context->getData($data);
        foreach ($this->context->getFilterPipeline() as $filter) {
            if ($filter['class'] instanceof FilterPipelineInterface) {
                $template = $filter['class']->setData($data)->filter($template);
            }
        }
        return $template;
    }
}