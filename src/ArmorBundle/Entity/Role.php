<?php

namespace ArmorBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;

class Role implements RoleInterface
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
     * Role constructor.
     * @param string|null $role
     */
    public function __construct(string $role = null)
    {
        $this->role = $role;
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
     * @param mixed $user
     * @return RoleInterface
     */
    public function setUser($user) : RoleInterface
    {
        $this->user = $user;

        return $this;
    }
    /**
     * @return null|string
     */
    public function getRole()
    {
        return $this->role;
    }
}