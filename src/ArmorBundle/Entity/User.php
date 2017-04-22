<?php

namespace ArmorBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var string $lastname
     */
    private $lastname;
    /**
     * @var string $username
     */
    private $username;
    /**
     * @var string $password
     */
    private $password;

    private $plainPassword;
    /**
     * @var string $dateCreated
     */
    private $dateCreated;
    /**
     * @var bool $enabled
     */
    private $enabled;
    /**
     * @var ArrayCollection $roles
     */
    private $roles;
    /**
     * @var string $gender
     */
    private $gender;

    public function __construct()
    {
        $this->dateCreated = new \DateTime();
        $this->roles = new ArrayCollection();
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }
    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }
    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }
    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }
    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }
    /**
     * @param Role $role
     * @return $this
     */
    public function addRole(Role $role)
    {
        if ($this->roles->contains($role)) {
            return $this;
        }

        $role->setUser($this);

        $this->roles->add($role);

        return $this;
    }
    /**
     $u* @return array
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }
    /**
     * @param Role $role
     * @return bool
     */
    public function hasRole(Role $role) : bool
    {
        return $this->roles->contains($role);
    }
    /**
     * @param array $roles
     */
    public function setRoles(array $roles)
    {
        foreach ($roles as $role) {
            if (!$role instanceof Role) {
                continue;
            }

            if (!$this->hasRole($role)) {
                $this->addRole($role);
            }
        }
    }
    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }
    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }
    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->roles,
        ));
    }
    /**
     * @param $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->roles,
            ) = unserialize($serialized);
    }
}
