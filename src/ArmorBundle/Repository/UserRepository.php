<?php

namespace ArmorBundle\Repository;

use ArmorBundle\Entity\User;
use BlueDot\BlueDotInterface;
use BlueDot\Entity\Promise;
use BlueDot\Entity\PromiseInterface;

class UserRepository
{
    /**
     * @var BlueDotInterface $blueDot
     */
    private $blueDot;
    /**
     * UserRepository constructor.
     * @param BlueDotInterface $blueDot
     */
    public function __construct(BlueDotInterface $blueDot)
    {
        $blueDot->useApi('user');

        $this->blueDot = $blueDot;
    }
    /**
     * @param string $username
     * @return mixed
     */
    public function findUserByUsername(string $username)
    {
        return $this->blueDot->execute('simple.select.find_user_by_username', array(
            'username' => $username,
        ));
    }
    /**
     * @param User $user
     * @return PromiseInterface
     */
    public function createUser(User $user)
    {
        return $this->findUserByUsername($user->getUsername())
            ->success(function() {
                return new Promise();
            })
            ->failure(function() use ($user) {
                return $this->blueDot->execute('simple.insert.create_user', array(
                    'username' => $user->getUsername(),
                    'name' => $user->getName(),
                    'lastname' => $user->getLastname(),
                    'password' => $user->getPassword(),
                    'gender' => $user->getGender(),
                    'roles' => serialize($user->getRoles()),
                ));
            })
            ->getResult();
    }
}