<?php

namespace ArmorBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
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
}