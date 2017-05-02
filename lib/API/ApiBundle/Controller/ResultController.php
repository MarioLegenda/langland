<?php

namespace API\ApiBundle\Controller;

class ResultController extends JsonController
{
    public function resultAction()
    {
        return $this->createResponse($this->get('api.blue_dot.result_resolver'));
    }
}