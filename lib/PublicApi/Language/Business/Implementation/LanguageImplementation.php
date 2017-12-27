<?php

namespace PublicApi\Language\Business\Implementation;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use BlueDot\Entity\PromiseInterface;
use PublicApi\Language\Repository\LanguageRepository;

class LanguageImplementation
{
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * LanguageImplementation constructor.
     * @param LanguageRepository $languageRepository
     */
    public function __construct(
        LanguageRepository $languageRepository
    ) {
        $this->languageRepository = $languageRepository;
    }
    /**
     * @return array
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function findAll()
    {
        /** @var PromiseInterface $languages */
        $promise = $this->languageRepository->findAll('callable.find_all_languages');

        /** @var array $result */
        $result = $promise->success(function(PromiseInterface $promise) {
            $languages = $promise->getResult()->toArray();

            foreach ($languages as $key => $language) {
                $languages[$key]['images'] = json_decode($language['images'], true);
            }

            return $languages;
        })->failure(function() {
            return [];
        })->getResult();

        return $result;
    }
    /**
     * @param int $languageId
     * @param User $user
     */
    public function registerLearningUser(int $languageId, User $user)
    {
        $language = $this->getLanguage($languageId);
    }
    /**
     * @param int|Language $language
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     * @return Language
     */
    private function getLanguage($language): Language
    {
        if (is_int($language)) {
            /** @var PromiseInterface $promise */
            $promise = $this->languageRepository->find($language, 'simple.select.find_by_id');

            if ($promise->isFailure()) {
                $message = sprintf('Language with id %d not found', $language);
                throw new \RuntimeException($message);
            }

            $language = $promise->getResult();
        }

        if (!$language instanceof Language) {
            $message = sprintf('Language with id %d not found', $language);
            throw new \RuntimeException($message);
        }

        return $language;
    }
}