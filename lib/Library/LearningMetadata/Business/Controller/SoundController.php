<?php

namespace Library\LearningMetadata\Business\Controller;

use Library\LearningMetadata\Business\Implementation\SoundImplementation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SoundController
{
    /**
     * @var SoundImplementation $soundImplementation
     */
    private $soundImplementation;
    /**
     * SoundController constructor.
     * @param SoundImplementation $soundImplementation
     */
    public function __construct(
        SoundImplementation $soundImplementation
    ) {
        $this->soundImplementation = $soundImplementation;
    }
    /**
     * @return Response
     */
    public function indexAction() : Response
    {
        return $this->soundImplementation->getListPresentation();
    }
    /**
     * @return Response
     */
    public function createAction()
    {
        return $this->soundImplementation->getCreatePresentation();
    }
    /**
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->soundImplementation->newSound($request);
    }
}