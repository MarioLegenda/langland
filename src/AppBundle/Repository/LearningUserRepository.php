<?php

namespace AppBundle\Repository;

use AdminBundle\Entity\Language;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class LearningUserRepository extends EntityRepository
{
    /**
     * @param UserInterface $user
     * @return null
     */
    public function findLearningUserByLoggedInUser(UserInterface $user)
    {
        $user = $this->findBy(array(
            'user' => $user,
        ));

        if (!empty($user)) {
            return $user[0];
        }

        return null;
    }
    /**
     * @param Language $language
     * @return array|null
     */
    public function findLearningUserByLanguage(Language $language)
    {
        $qb = $this->createQueryBuilder('lu');

        $result = $qb
            ->innerJoin('lu.languages', 'l')
            ->where('l.id = :user_id')
            ->setParameter(':user_id', $language->getId())
            ->getQuery()
            ->getResult();

        if (empty($result)) {
            return null;
        }

        return $result;
    }
}