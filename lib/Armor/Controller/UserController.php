<?php

namespace Armor\Controller;

use ApiSDK\ApiSDK;
use Armor\Domain\UserLogic;
use ArmorBundle\Entity\User;
use Library\Infrastructure\Helper\SerializerWrapper;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController
{
    /**
     * @var SerializerWrapper $serializerWrapper
     */
    private $serializerWrapper;
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * @var UserLogic $userLogic
     */
    private $userLogic;
    /**
     * UserController constructor.
     * @param UserLogic $userLogic
     * @param SerializerWrapper $serializerWrapper
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        UserLogic $userLogic,
        SerializerWrapper $serializerWrapper,
        ApiSDK $apiSDK
    ) {
        $this->serializerWrapper = $serializerWrapper;
        $this->apiSdk = $apiSDK;
        $this->userLogic = $userLogic;
    }
    /**
     * @param User $user
     * @return JsonResponse
     */
    public function getLoggedInPublicUserAction(User $user)
    {
        $response = $this->apiSdk
            ->create($this->serializerWrapper->normalize($user, 'default'))
            ->isResource()
            ->method('GET')
            ->setStatusCode(200)
            ->build();

        return new JsonResponse(
            $response,
            200
        );
    }
}