<?php

namespace Library\LearningMetadata\Business\Controller;

use Library\LearningMetadata\Business\Implementation\LanguageImplementation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @return Response
     */
    public function indexAction() : Response
    {
        return $this->languageImplementation->getListPresentation();
    }
    /**
     * @return Response
     */
    public function createAction()
    {
        return $this->languageImplementation->getCreatePresentation();
    }
    /**
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->languageImplementation->newLanguage($request);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateAction(Request $request, int $id)
    {
        return $this->languageImplementation->updateLanguage($request, $id);
    }
}