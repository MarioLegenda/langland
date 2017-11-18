<?php

namespace Library\LearningMetadata\Business\Controller;

use Library\LearningMetadata\Business\Implementation\LanguageImplementation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class LanguageController
{
    /**
     * @var LanguageImplementation $languageImplementation
     */
    private $languageImplementation;
    /**
     * LanguageController constructor.
     * @param LanguageImplementation $languageImplementation
     */
    public function __construct(
        LanguageImplementation $languageImplementation
    ) {
        $this->languageImplementation = $languageImplementation;
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_VIEW')")
     *
     * @return Response
     */
    public function indexAction() : Response
    {
        return $this->languageImplementation->getListPresentation();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @return Response
     */
    public function createAction()
    {
        return $this->languageImplementation->getCreatePresentation();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->languageImplementation->newLanguage($request);
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateAction(Request $request, int $id)
    {
        return $this->languageImplementation->updateLanguage($request, $id);
    }
}