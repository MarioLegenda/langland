<?php

namespace AppBundle\Controller\Api;

use AdminBundle\Controller\RepositoryController;
use JMS\Serializer\SerializationContext;
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
            'id' => $user->getId(),
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