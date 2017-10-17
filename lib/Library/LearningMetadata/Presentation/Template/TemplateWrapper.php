<?php

namespace Library\LearningMetadata\Presentation\Template;

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
     * @param $data
     * @return string
     */
    public function getTemplate(string $template, $data) : string
    {
        return $this->templating->render($template, $data);
    }
}