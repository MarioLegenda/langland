<?php

namespace ArmorBundle\Repository;

use ArmorBundle\Entity\User;
use Library\Infrastructure\Repository\CommonRepository;

class UserRepository extends CommonRepository
{
    /**
     * @param string $username
     * @return mixed
     */
    public function findUserByUsername(string $username)
    {
        return $this->findBy(array(
            'username' => $username,
        ));
    }
    /**
     * @param string $confirmHash
     * @return array
     */
    public function findUserByConfirmationHash(string $confirmHash)
    {
        return $this->findBy(array(
            'confirmHash' => $confirmHash,
        ));
    }
    /**
     * @param User $user
     * @return User
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(User $user): User
    {
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}