<?php

namespace PublicApi\Language\Business\Controller;

use Library\Infrastructure\Helper\CommonSerializer;
use PublicApi\Language\Business\Implementation\LanguageImplementation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @return Response
     */
    public function getAll(): Response
    {
        $data = $this->commonSerializer->serializeMany(
            $this->languageImplementation->findAll(),
            ['viewable']
        );

        return new Response($data, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}