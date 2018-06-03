<?php

namespace PublicApi\Language\Business\Controller;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use PublicApi\Language\Business\Implementation\LanguageImplementation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LanguageController
{
    /**
     * @var LanguageImplementation $languageImplementation
     */
    private $languageImplementation;
    /**
     * LanguageController constructor.
     * @param LanguageImplementation $languageImplementation
     */
    public function __construct(
        LanguageImplementation $languageImplementation
    ) {
        $this->languageImplementation = $languageImplementation;
    }
    /**
     * @param User $user
     * @return Response
     */
    public function getAllShowableLanguages(User $user): Response
    {
        return new JsonResponse(
            $this->languageImplementation->createLanguagePresentation($user),
            200
        );
    }
    /**
     * @param Language $language
     * @return Response
     */
    public function getLanguageInfo(Language $language): Response
    {
        return new JsonResponse(
            $this->languageImplementation->findLanguageInfo($language),
            200
        );
    }
}