<?php

namespace PublicApi\Language\Business\Controller;

use AdminBundle\Entity\Language;
use AdminBundle\Entity\LanguageInfo;
use ArmorBundle\Entity\User;
use Library\Infrastructure\Helper\CommonSerializer;
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
     * @var CommonSerializer $commonSerializer
     */
    private $commonSerializer;
    /**
     * LanguageController constructor.
     * @param LanguageImplementation $languageImplementation
     * @param CommonSerializer $commonSerializer
     */
    public function __construct(
        LanguageImplementation $languageImplementation,
        CommonSerializer $commonSerializer
    ) {
        $this->languageImplementation = $languageImplementation;
        $this->commonSerializer = $commonSerializer;
    }
    /**
     * @Security("has_role('ROLE_PUBLIC_API_USER')")
     *
     * @param User $user
     * @return Response
     */
    public function getAll(User $user): Response
    {
        $data = $this->languageImplementation->findAllWithAlreadyLearning($user);

        return new JsonResponse($data, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
    /**
     * @Security("has_role('ROLE_PUBLIC_API_USER')")
     *
     * @param Language $language
     * @return Response
     */
    public function getLanguageInfo(Language $language): Response
    {
        $languageInfo = $this->languageImplementation->findLanguageInfo($language);

        $serialized = $this->commonSerializer->serialize($languageInfo, ['language_info'], 'json');

        return new Response($serialized, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}