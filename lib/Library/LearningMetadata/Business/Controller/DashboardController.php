<?php

namespace Library\LearningMetadata\Business\Controller;

use Library\LearningMetadata\Presentation\Template\TemplateWrapper;
use Symfony\Component\HttpFoundation\Response;

class DashboardController
{
    /**
     * @var TemplateWrapper $templateWrapper
     */
    private $templateWrapper;
    /**
     * DashboardController constructor.
     * @param TemplateWrapper $templateWrapper
     */
    public function __construct(
        TemplateWrapper $templateWrapper
    ) {
        $this->templateWrapper = $templateWrapper;
    }
    /**
     * @return string
     */
    public function dashboardAction()
    {
        return new Response($this->templateWrapper->getTemplate('::Admin/Template/Panel/Dashboard/dashboard.html.twig'));
    }
}