<?php

namespace PublicApi\Infrastructure\Resolver;

use AdminBundle\Entity\Language;
use PublicApi\Language\Repository\LanguageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class LanguageValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * @var Language $language
     */
    private $language;
    /**
     * PostMethodLanguageValueResolver constructor.
     * @param LanguageRepository $languageRepository
     */
    public function __construct(
        LanguageRepository $languageRepository
    ) {
        $this->languageRepository = $languageRepository;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (Language::class !== $argument->getType()) {
            return false;
        }

        $languageId = $request->request->get('languageId');

        if (is_null($languageId)) {
            $languageId = $request->query->get('languageId');

            if (is_null($languageId)) {
                $languageId = $request->get('languageId');
            }
        }

        if (is_numeric($languageId)) {
            $languageId = (int) $languageId;
        }

        $language = $this->languageRepository->find($languageId);

        if (!$language instanceof Language) {
            return false;
        }

        $this->language = $language;

        return true;
    }
    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return \Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        yield $this->language;
    }
}