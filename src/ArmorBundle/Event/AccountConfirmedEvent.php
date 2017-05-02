<?php

namespace ArmorBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountConfirmedEvent extends Event
{
    const NAME = 'armor.account_confirmed';

    private $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
}