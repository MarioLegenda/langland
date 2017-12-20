<?php

namespace LearningMetadata\Business\Controller;

use LearningMetadata\Business\Implementation\SoundImplementation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @return Response
     */
    public function indexAction() : Response
    {
        return $this->soundImplementation->getListPresentation();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @return Response
     */
    public function createAction()
    {
        return $this->soundImplementation->getCreatePresentation();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->soundImplementation->newSound($request);
    }
}