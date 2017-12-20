<?php

namespace LearningMetadata\Business\Controller;

use LearningMetadata\Presentation\Template\TemplateWrapper;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Security("has_role('ROLE_ALLOWED_VIEW')")
     * @return string
     */
    public function dashboardAction()
    {
        return new Response($this->templateWrapper->getTemplate('::Admin/Template/Panel/Dashboard/dashboard.html.twig'));
    }
}