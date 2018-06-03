<?php

namespace PublicApi\Language\Repository;

use AdminBundle\Entity\Language;
use Library\Infrastructure\Repository\CommonRepository;
use ArmorBundle\Entity\User;

class LanguageRepository extends CommonRepository
{
    /**
     * @param User $user
     * @return array
     */
    public function getSortedLanguages(User $user): array
    {
        $learningMetadataLanguages = $this->findBy([
            'showOnPage' => true,
        ]);

        $alreadyLearningLanguages = $user->getLanguageSessionLanguages();

        $notLearningLanguages = array_filter($learningMetadataLanguages, function(Language $language) use ($alreadyLearningLanguages) {
            /** @var Language $alreadyLearningLanguage */
            foreach ($alreadyLearningLanguages as $alreadyLearningLanguage) {
                if ($alreadyLearningLanguage->getId() !== $language->getId()) {
                    return true;
                }
            }
        }, ARRAY_FILTER_USE_BOTH);

        $returnData = [
            'alreadyLearning' => $alreadyLearningLanguages,
            'notLearning' => $notLearningLanguages,
        ];

        return $returnData;
    }
}