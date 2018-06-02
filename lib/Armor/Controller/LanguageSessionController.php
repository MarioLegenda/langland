<?php

namespace Armor\Controller;

use ApiSDK\ApiSDK;
use Armor\Infrastructure\Communicator\Session\LanguageSessionCommunicator;
use Armor\Infrastructure\Model\Language;
use ArmorBundle\Entity\LanguageSession;
use ArmorBundle\Entity\User;
use Armor\Domain\LanguageSessionLogic;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * LearningUserController constructor.
     * @param LanguageSessionLogic $languageSessionLogic
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        LanguageSessionLogic $languageSessionLogic,
        ApiSDK $apiSDK
    ) {
        $this->languageSessionLogic = $languageSessionLogic;
        $this->apiSdk = $apiSDK;
    }

    public function registerLanguageSession(
        LanguageSessionCommunicator $languageSessionCommunicatorUser,
        User $user
    ): JsonResponse {
        /** @var LanguageSession $languageSession */
        $languageSession = $this->languageSessionLogic->createAndRegisterLanguageSession(
            $languageSessionCommunicatorUser,
            $user
        );

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

        return new JsonResponse(
            $response,
            201
        );
    }

    public function updateLanguageSession(Language $language, User $user)
    {

    }
}