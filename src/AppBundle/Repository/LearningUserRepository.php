<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class LearningUserRepository extends EntityRepository
{
    public function findLearningUserByLoggedInUser(UserInterface $user)
    {
        return $this->findBy(array(
            'user' => $user,
        ));
    }
}