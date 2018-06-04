<?php

namespace Armor\Controller;

use ApiSDK\ApiSDK;
use Armor\Infrastructure\Communicator\Session\LanguageSessionCommunicator;
use ArmorBundle\Entity\LanguageSession;
use ArmorBundle\Entity\User;
use Armor\Domain\LanguageSessionLogic;
use Library\Infrastructure\Helper\SerializerWrapper;
use Library\Util\Util;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LanguageSessionController
{
    /**
     * @var LanguageSessionLogic $languageSessionLogic
     */
    private $languageSessionLogic;
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * @var SerializerWrapper $serializerWrapper
     */
    private $serializerWrapper;
    /**
     * LearningUserController constructor.
     * @param LanguageSessionLogic $languageSessionLogic
     * @param ApiSDK $apiSDK
     * @param SerializerWrapper $serializerWrapper
     */
    public function __construct(
        LanguageSessionLogic $languageSessionLogic,
        ApiSDK $apiSDK,
        SerializerWrapper $serializerWrapper
    ) {
        $this->languageSessionLogic = $languageSessionLogic;
        $this->apiSdk = $apiSDK;
        $this->serializerWrapper = $serializerWrapper;
    }

    /**
     * @param LanguageSessionCommunicator $languageSessionCommunicator
     * @param User $user
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function registerLanguageSession(
        LanguageSessionCommunicator $languageSessionCommunicator,
        User $user
    ): JsonResponse {
        /** @var LanguageSession $languageSession */
        $languageSession = $this->languageSessionLogic->createAndRegisterLanguageSession(
            $languageSessionCommunicator,
            $user
        );

        return new JsonResponse(
            $this->createDataResponseForSessionRegistration($languageSession, $user),
            201
        );
    }
    /**
     * @param LanguageSession $languageSession
     * @param User $user
     * @return JsonResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changeLanguageSession(LanguageSession $languageSession, User $user)
    {
        $languageSession = $this->languageSessionLogic->changeLanguageSession($languageSession, $user);

        return new JsonResponse(
            $this->createDataResponseForSessionRegistration($languageSession, $user),
            201
        );
    }
    /**
     * @param User $user
     * @return JsonResponse
     */
    public function getCurrentLanguageSession(User $user)
    {
        $languageSession = $user->getCurrentLanguageSession();

        $serialized = $this->serializerWrapper->serialize($languageSession, ['default']);

        $data = json_decode($serialized, true);

        $response = $this->apiSdk
            ->create($data)
            ->isResource()
            ->method('GET')
            ->setStatusCode(200)
            ->build();

        return new JsonResponse(
            $response,
            201
        );
    }
    /**
     * @param LanguageSession $languageSession
     * @param User $user
     * @return array
     */
    private function createDataResponseForSessionRegistration(
        LanguageSession $languageSession,
        User $user
    ): array {
        $languageSessionId = $languageSession->getLearningUser()->getId();
        $languageId = $languageSession->getLearningUser()->getLanguage()->getId();
        $languageName = $languageSession->getLearningUser()->getLanguage()->getName();
        $learningUserId = $languageSession->getLearningUser()->getId();

        $createdAt = $languageSession->getCreatedAt()->format('Y-m-d H:m:s');
        $updatedAt = $languageSession->getUpdatedAt()->format('Y-m-d H:m:s');

        $data = [
            'id' => $languageSessionId,
            'language' => [
                'id' => $languageId,
                'name' => $languageName,
            ],
            'languageSessions' => Util::extractFieldFromObjects(
                $user->getLanguageSessions(),
                'id'
            ),
            'learningUserId' => $learningUserId,
            'createdAt' => $createdAt,
            'updatedAt' => $updatedAt,
        ];

        $response = $this->apiSdk
            ->create($data)
            ->isResource()
            ->method('POST')
            ->setStatusCode(201)
            ->build();

        return $response;
    }
}