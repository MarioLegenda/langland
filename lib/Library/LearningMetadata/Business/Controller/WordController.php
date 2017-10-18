<?php

namespace Library\LearningMetadata\Business\Controller;

use Library\LearningMetadata\Business\Implementation\WordImplementation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @return Response
     */
    public function indexAction()
    {
        return $this->wordImplementation->getListPresentation();
    }
    /**
     * @return Response
     */
    public function createAction()
    {
        return $this->wordImplementation->getCreatePresentation();
    }
    /**
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->wordImplementation->newWord($request);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateAction(Request $request, int $id)
    {
        return $this->wordImplementation->updateWord($request, $id);
    }
}