<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class LearningUserRepository extends EntityRepository
{
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
}