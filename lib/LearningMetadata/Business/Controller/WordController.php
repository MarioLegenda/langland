<?php

namespace LearningMetadata\Business\Controller;

use LearningMetadata\Business\Implementation\WordImplementation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class WordController
{
    /**
     * @var WordImplementation $wordImplementation
     */
    private $wordImplementation;
    /**
     * WordController constructor.
     * @param WordImplementation $wordImplementation
     */
    public function __construct(
        WordImplementation $wordImplementation
    ) {
        $this->wordImplementation = $wordImplementation;
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_VIEW')")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->wordImplementation->getListPresentation();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @return Response
     */
    public function createAction()
    {
        return $this->wordImplementation->getCreatePresentation();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->wordImplementation->newWord($request);
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
        return $this->wordImplementation->updateWord($request, $id);
    }
}