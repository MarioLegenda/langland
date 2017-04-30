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

    private $confirmHash;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;

    public function __construct()
    {
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $name
     * @return UserInterface
     */
    public function setName($name) : UserInterface
    {
        $this->name = $name;

        return $this;
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
     * @return UserInterface
     */
    public function setLastname($lastname) : UserInterface
    {
        $this->lastname = $lastname;

        return $this;
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
     * @return UserInterface
     */
    public function setUsername($username) : UserInterface
    {
        $this->username = $username;

        return $this;
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
     * @return UserInterface
     */
    public function setPassword($password) : UserInterface
    {
        $this->password = $password;

        return $this;
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
    public function getEnabled()
    {
        return $this->enabled;
    }
    /**
     * @param mixed $enabled
     * @return UserInterface
     */
    public function setEnabled($enabled) : UserInterface
    {
        $this->enabled = $enabled;

        return $this;
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
     * @return UserInterface
     */
    public function addRole(Role $role) : UserInterface
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
     * @return UserInterface
     */
    public function setGender($gender) : UserInterface
    {
        $this->gender = $gender;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getConfirmHash()
    {
        return $this->confirmHash;
    }
    /**
     * @param mixed $confirmHash
     * @return UserInterface
     */
    public function setConfirmHash($confirmHash) : UserInterface
    {
        $this->confirmHash = $confirmHash;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime $createdAt
     * @return UserInterface
     */
    public function setCreatedAt(\DateTime $createdAt) : UserInterface
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime $updatedAt
     * @return UserInterface
     */
    public function setUpdatedAt(\DateTime $updatedAt) : UserInterface
    {
        $this->updatedAt = $updatedAt;

        return $this;
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

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}
