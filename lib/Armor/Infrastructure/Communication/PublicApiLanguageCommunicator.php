<?php

namespace Armor\Infrastructure\Communication;

use Armor\Infrastructure\Model\Language;
use Armor\Repository\PublicApiLanguageRepository;
use Library\Util\Util;
use PublicApiBundle\Entity\PublicApiLanguage;

class PublicApiLanguageCommunicator
{
    /**
     * @var PublicApiLanguageRepository
     */
    private $publicApiLanguageRepository;
    /**
     * PublicApiLanguageCommunicator constructor.
     * @param PublicApiLanguageRepository $publicApiLanguageRepository
     */
    public function __construct(
        PublicApiLanguageRepository $publicApiLanguageRepository
    ) {
        $this->publicApiLanguageRepository = $publicApiLanguageRepository;
    }
    /**
     * @param Language $language
     * @param array $diffFields
     * @return PublicApiLanguage
     */
    public function updatePublicApiLanguageFromLanguage(Language $language, array $diffFields): PublicApiLanguage
    {
        /** @var PublicApiLanguage $publicApiLanguage */
        $publicApiLanguage = $this->publicApiLanguageRepository->findOneBy([
            'name' => $language->getName(),
        ]);

        Util::setObjectFieldsByConvention($publicApiLanguage, $diffFields);

        $this->publicApiLanguageRepository->persistAndFlush($publicApiLanguage);

        return $publicApiLanguage;
    }
}