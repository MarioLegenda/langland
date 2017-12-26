<?php

namespace PublicApi\Language\Business\Implementation;

use BlueDot\Entity\Entity;
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
}