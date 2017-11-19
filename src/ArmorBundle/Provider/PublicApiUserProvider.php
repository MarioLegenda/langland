<?php

namespace ArmorBundle\Provider;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use ArmorBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class PublicApiUserProvider implements UserProviderInterface
{
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * @var TokenStorageInterface $tokenStorage
     */
    private $tokenStorage;
    /**
     * UserProvider constructor.
     * @param EntityManager $em
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        EntityManager $em,
        TokenStorageInterface $tokenStorage
    ) {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @param string $username
     * @throws UsernameNotFoundException
     * @return User
     */
    public function loadUserByUsername($username)
    {
        /** @var User $user */
        $user = $this->em->getRepository('ArmorBundle:User')->findBy(array(
            'username' => $username,
        ));

        if (!empty($user)) {
            if ($user[0] instanceof User) {
                return $user[0];
            }
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }
    /**
     * @param UserInterface $user
     * @return User
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }
    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}