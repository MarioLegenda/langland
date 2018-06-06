<?php

namespace LearningMetadata\Infrastructure\Communication;

use AdminBundle\Entity\Language;
use Library\Infrastructure\Helper\SerializerWrapper;
use Library\Util\Util;
use PublicApi\Language\Repository\PublicApiLanguageRepository;
use PublicApiBundle\Entity\PublicApiLanguage;

class PublicApiLanguageCommunicator
{
    /**
     * @var PublicApiLanguageRepository $publicApiLanguageRepository
     */
    private $publicApiLanguageRepository;
    /**
     * @var SerializerWrapper $serializerWrapper
     */
    private $serializerWrapper;
    /**
     * PublicApiLanguageCommunicator constructor.
     * @param PublicApiLanguageRepository $publicApiLanguageRepository
     * @param SerializerWrapper $serializerWrapper
     */
    public function __construct(
        PublicApiLanguageRepository $publicApiLanguageRepository,
        SerializerWrapper $serializerWrapper
    ) {
        $this->publicApiLanguageRepository = $publicApiLanguageRepository;
        $this->serializerWrapper = $serializerWrapper;
    }
    /**
     * @param Language $language
     * @param array $diffFields
     */
    public function createPublicApiLanguageFromLanguage(Language $language, array $diffFields): void
    {
        /** @var PublicApiLanguage $publicApiLanguage */
        $publicApiLanguage = $this->serializerWrapper->convertFromTo(
            $language,
            'default',
            PublicApiLanguage::class,
            false
        );

        Util::setObjectFieldsByConvention($publicApiLanguage, $diffFields);

        $this->serializerWrapper->getModelValidator()->validate($publicApiLanguage);

        $this->publicApiLanguageRepository->persistAndFlush($publicApiLanguage);
    }
    /**
     * @param Language $language
     * @param array $diffFields
     */
    public function updatePublicApiLanguageFromLanguage(Language $language, array $diffFields)
    {
        /** @var PublicApiLanguage $publicApiLanguage */
        $publicApiLanguage = $this->serializerWrapper->convertFromTo(
            $language,
            'default',
            PublicApiLanguage::class,
            false
        );

        $existingPublicApiLanguage = $this->publicApiLanguageRepository->find($publicApiLanguage->getId());

        if ($existingPublicApiLanguage instanceof PublicApiLanguage) {
            $publicApiLanguage = $existingPublicApiLanguage;
        }

        Util::setObjectFieldsByConvention($publicApiLanguage, $diffFields);

        $this->serializerWrapper->getModelValidator()->validate($publicApiLanguage);

        $this->publicApiLanguageRepository->persistAndFlush($publicApiLanguage);
    }
}