<?php

namespace PublicApi\StaticPage\Business\Controller;

use Library\Presentation\Template\TemplateWrapper;
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
        $template = $this->templateWrapper->getTemplate('::App/Static/index.html.twig');

        return new Response($template);
    }
    /**
     * @Security("has_role('ROLE_USER')")
     *
     * @return Response
     */
    public function appAction(): Response
    {
        $template = $this->templateWrapper->getTemplate('::App/Static/app.html.twig');

        return new Response($template);
    }
    /**
     * @Security("has_role('ROLE_USER')")
     *
     * @return Response
     */
    public function lessonRunnerAction(): Response
    {
        $template = $this->templateWrapper->getTemplate('::App/Static/app.html.twig');

        return new Response($template);
    }
    /**
     * @Security("has_role('ROLE_USER')")
     *
     * @return Response
     */
    public function gameRunnerAction(): Response
    {
        $template = $this->templateWrapper->getTemplate('::App/Static/app.html.twig');

        return new Response($template);
    }
}