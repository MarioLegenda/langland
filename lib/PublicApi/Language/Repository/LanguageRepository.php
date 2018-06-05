<?php

namespace PublicApi\Language\Repository;

use AdminBundle\Entity\Language;
use Library\Infrastructure\Repository\CommonRepository;
use ArmorBundle\Entity\User;
use Library\Util\Util;

class LanguageRepository extends CommonRepository
{
    /**
     * @param User $user
     * @return array
     */
    public function getSortedLanguages(User $user): array
    {
        /** @var Language[] $learningMetadataLanguages */
        $learningMetadataLanguages = $this->findBy([
            'showOnPage' => true,
        ]);

        $alreadyLearningLanguages = $user->getLanguageSessionLanguages();

        $notLearningLanguages = [];
        if (!empty($alreadyLearningLanguages)) {
            $notLearningLanguages = $this->filterNotLearningLanguages(
                $learningMetadataLanguages,
                $alreadyLearningLanguages
            );
        }

        $returnData = [
            'alreadyLearning' => $alreadyLearningLanguages,
            'notLearning' => $notLearningLanguages,
        ];

        return $returnData;
    }
    /**
     * @param Language[] $learningMetadataLanguages
     * @param Language[] $alreadyLearningLanguages
     * @return Language[]
     */
    private function filterNotLearningLanguages(
        array $learningMetadataLanguages,
        array $alreadyLearningLanguages
    ): array {
        $learningMetadataLanguagesIds = Util::extractFieldFromObjects($learningMetadataLanguages, 'id');
        $alreadyLearningLanguagesIds = Util::extractFieldFromObjects($alreadyLearningLanguages, 'id');

        $notLearningLanguageIds = array_diff($learningMetadataLanguagesIds, $alreadyLearningLanguagesIds);

        if (!empty($notLearningLanguageIds)) {
            return array_filter($learningMetadataLanguages, function(Language $language) use ($notLearningLanguageIds) {
                return in_array($language->getId(), $notLearningLanguageIds);
            }, ARRAY_FILTER_USE_BOTH);
        }

        return [];
    }
}