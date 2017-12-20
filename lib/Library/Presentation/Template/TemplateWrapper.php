<?php

namespace Library\Presentation\Template;

use Symfony\Bundle\TwigBundle\TwigEngine;

class TemplateWrapper
{
    /**
     * @var TwigEngine $templating
     */
    private $templating;
    /**
     * TemplateWrapper constructor.
     * @param TwigEngine $templating
     */
    public function __construct(TwigEngine $templating)
    {
        $this->templating = $templating;
    }
    /**
     * @param string $template
     * @param array $data
     * @return string
     */
    public function getTemplate(string $template, array $data = array()) : string
    {
        return $this->templating->render($template, $data);
    }
}