<?php

namespace Library\LearningMetadata\Business\Controller;

use Library\LearningMetadata\Business\Implementation\LanguageInfoImplementation;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LanguageInfoController
{
    /**
     * @var LanguageInfoImplementation $languageInfoImplementation
     */
    private $languageInfoImplementation;
    /**
     * LanguageInfoController constructor.
     * @param LanguageInfoImplementation $languageInfoImplementation
     */
    public function __construct(
        LanguageInfoImplementation $languageInfoImplementation
    ) {
        $this->languageInfoImplementation = $languageInfoImplementation;
    }
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->languageInfoImplementation->getListPresentation();
    }
    /**
     * @return Response
     */
    public function createAction()
    {
        return $this->languageInfoImplementation->getCreatePresentation();
    }
    /**
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->languageInfoImplementation->newLanguageInfo($request);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateAction(Request $request, int $id)
    {
        return $this->languageInfoImplementation->updateLanguageInfo($request, $id);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteAction(Request $request, int $id)
    {
        return $this->languageInfoImplementation->removeLanguageInfo($id);
    }
}