<?php

namespace AdminBundle\Controller\Ajax;

use Library\CommonController;

class GameTypeController extends CommonController
{
    public function findGameTypesAction()
    {
        $gameTypes = $this->getRepository('AdminBundle:GameType')->findAll();

        if (empty($gameTypes)) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse(
            $this->serialize($gameTypes, array('game_types'))
        );
    }
}