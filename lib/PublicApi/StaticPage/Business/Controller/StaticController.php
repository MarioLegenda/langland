<?php

namespace PublicApi\StaticPage\Business\Controller;

use LearningMetadata\Presentation\Template\TemplateWrapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

class StaticController
{
    /**
     * @var TemplateWrapper $templateWrapper
     */
    private $templateWrapper;
    /**
     * StaticController constructor.
     * @param TemplateWrapper $templateWrapper
     */
    public function __construct(
        TemplateWrapper $templateWrapper
    ) {
        $this->templateWrapper = $templateWrapper;
    }
    /**
     * @Security("has_role('ROLE_USER')")
     *
     * @return Response
     */
    public function languageIndexAction(): Response
    {
        $template = $this->templateWrapper->getTemplate('::App/Static/Language/index.html.twig');

        return new Response($template);
    }
}