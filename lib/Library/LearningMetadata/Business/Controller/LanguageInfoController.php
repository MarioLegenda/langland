<?php

namespace Library\LearningMetadata\Business\Controller;

use Library\LearningMetadata\Business\Implementation\LanguageInfoImplementation;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Security("has_role('ROLE_ALLOWED_VIEW')")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->languageInfoImplementation->getListPresentation();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @return Response
     */
    public function createAction()
    {
        return $this->languageInfoImplementation->getCreatePresentation();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->languageInfoImplementation->newLanguageInfo($request);
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
        return $this->languageInfoImplementation->updateLanguageInfo($request, $id);
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteAction(int $id)
    {
        return $this->languageInfoImplementation->removeLanguageInfo($id);
    }
}