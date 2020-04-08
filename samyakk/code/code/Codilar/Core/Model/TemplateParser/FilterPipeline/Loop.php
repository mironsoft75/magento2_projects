<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Core\Model\TemplateParser\FilterPipeline;


use Codilar\Core\Model\TemplateParser\FilterPipelineInterface;
use Codilar\Core\Model\TemplateParser\TemplateParser;
use Codilar\Core\Model\TemplateParser\TemplateParserFactory;

class Loop implements FilterPipelineInterface
{

    const FOR_REGEX = '/{{@for ([^ ]+?) as(?: ([^ ]+?) :)? ([^ ]+?)}}(.*?){{@endfor}}/sU';
    const DEFAULT_FOR_KEY = "_forKey";
    /**
     * @var TemplateParserFactory
     */
    private $templateParserFactory;
    /**
     * @var Variable
     */
    private $variable;

    /**
     * Loop constructor.
     * @param TemplateParserFactory $templateParserFactory
     * @param Variable $variable
     */
    public function __construct(
        TemplateParserFactory $templateParserFactory,
        Variable $variable
    )
    {
        $this->templateParserFactory = $templateParserFactory;
        $this->variable = $variable;
    }

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $template
     * @return string
     */
    public function filter($template)
    {
        /* Filter Loops */
        return preg_replace_callback(self::FOR_REGEX, [$this, '_filterLoop'], $template);
    }

    /**
     * @param array $matches
     * @return string
     */
    private function _filterLoop($matches) {
        $subject = $this->variable->getValue($this->data, $matches[1]);
        if (is_null($subject)) {
            return $matches[0];
        }
        if (!$matches[2]) {
            $matches[2] = self::DEFAULT_FOR_KEY;
        }
        $response = '';
        foreach ($subject as $key => $item) {
            /** @var TemplateParser $templateParser */
            $templateParser = $this->templateParserFactory->create();
            $response .= $templateParser->parse($matches[4], array_merge($this->data, [
                $matches[2] => $key,
                $matches[3] => $item
            ]));
        }
        return $response;
    }
}