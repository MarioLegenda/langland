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
     * @Security("has_role('ROLE_PUBLIC_API_USER')")
     *
     * @param User $user
     * @return Response
     */
    public function getAll(User $user): Response
    {
        return new JsonResponse(
            $this->languageImplementation->findAllWithAlreadyLearning($user),
            200
        );
    }
    /**
     * @Security("has_role('ROLE_PUBLIC_API_USER')")
     *
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