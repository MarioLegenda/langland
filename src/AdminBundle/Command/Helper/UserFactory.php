<?php

namespace AdminBundle\Command\Helper;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use ArmorBundle\Entity\Role;
use ArmorBundle\Entity\User;

class UserFactory
{
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * @var UserPasswordEncoder $encoder
     */
    private $encoder;
    /**
     * UserFactory constructor.
     * @param EntityManager $em
     * @param $encoder
     */
    public function __construct(EntityManager $em, $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public function create(string $username, string $password, array $roles) : UserFactory
    {
        $user = new User();
        $user->setUsername($username);
        $password = $this->encoder->encodePassword($user, $password);
        $user->setPassword($password);
        $user->setName('Mile');
        $user->setLastname('Mile');

        foreach ($roles as $role) {
            if (!$role instanceof Role) {
                $message = sprintf('Roles is not an instance of %s', Role::class);

                throw new \RuntimeException($message);
            }

            $user->addRole($role);
        }

        $user->setEnabled(true);

        $this->em->persist($user);
        $this->em->flush();

        return $this;
    }
}