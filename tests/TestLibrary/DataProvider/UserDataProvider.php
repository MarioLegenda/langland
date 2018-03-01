<?php

namespace TestLibrary\DataProvider;

use ArmorBundle\Entity\Role;
use ArmorBundle\Entity\User;
use ArmorBundle\Repository\UserRepository;
use Faker\Generator;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
use Tests\TestLibrary\DataProvider\DefaultDataProviderInterface;

class UserDataProvider implements DefaultDataProviderInterface
{
    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;
    /**
     * UserDataProvider constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Generator $faker
     * @return User
     */
    public function createDefault(Generator $faker): User
    {
        $user = new User();
        $user->setName($faker->name);
        $user->setEnabled(1);
        $user->setLastname($faker->name);
        $user->setRoles([
            new Role('ROLE_PUBLIC_API_USER'),
        ]);
        $user->setUsername($faker->email);
        $user->setPassword($faker->password);

        return $user;
    }
    /**
     * @param Generator $faker
     * @throws \Doctrine\ORM\OptimisticLockException
     * @return User
     */
    public function createDefaultDb(Generator $faker): User
    {
        return $this->userRepository->persistAndFlush($this->createDefault($faker));
    }
    /**
     * @return UserRepository
     */
    public function getRepository(): UserRepository
    {
        return $this->userRepository;
    }
}