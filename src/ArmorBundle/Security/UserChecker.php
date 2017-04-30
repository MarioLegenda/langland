<?php

namespace ArmorBundle\Security;

use ArmorBundle\Exception\AccountNotEnabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    /**
     * @param UserInterface $user
     */
    public function checkPreAuth(UserInterface $user)
    {
        return;
    }
    /**
     * @param UserInterface $user
     */
    public function checkPostAuth(UserInterface $user)
    {
        if (!$user->getEnabled()) {
            throw new AccountNotEnabledException(
                sprintf(
                    'This account is not confirmed. Please, check %s and confirm your account',
                    $user->getUsername()
                )
            );
        }
    }
}