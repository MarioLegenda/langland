<?php

namespace AppBundle\Controller\Ajax;

use AdminBundle\Controller\RepositoryController;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends RepositoryController
{
    public function findLoggedInUserAction()
    {
        $user = $this->getUser();

        if (!$user instanceof UserInterface) {
            return new JsonResponse(array(
                'status' => 'failed',
                'message' => 'No user',
            ));
        }

        $userInfo = array(
            'name' => $user->getName(),
            'lastname' => $user->getLastname(),
            'username' => $user->getUsername(),
        );

        return new JsonResponse(array(
            'status' => 'success',
            'data' => $userInfo,
        ));
    }
}