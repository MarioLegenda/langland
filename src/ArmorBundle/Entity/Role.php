<?php

namespace ArmorBundle\Entity;

use Symfony\Component\Security\Core\Role\Role as SymfonyRole;

class Role extends SymfonyRole
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $role
     */
    private $role;
    /**
     * @var User $user
     */
    private $user;
    /**
     * @param string $role The role name
     */
    public function __construct($role)
    {
        $this->role = (string) $role;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @param User $user
     * @return SymfonyRole
     */
    public function setUser($user) : SymfonyRole
    {
        $this->user = $user;

        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function getRole()
    {
        return $this->role;
    }
}