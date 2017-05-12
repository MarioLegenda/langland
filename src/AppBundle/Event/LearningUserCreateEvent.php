<?php

namespace AppBundle\Event;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\Event;

class LearningUserCreateEvent extends Event
{
    const NAME = 'app.create_learning_user';
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * @var Language $language
     */
    private $language;
    /**
     * @var User $user
     */
    private $user;
    /**
     * LearningUserCreateEvent constructor.
     * @param EntityManager $em
     * @param Language $language
     */
    public function __construct(EntityManager $em, Language $language, User $user)
    {
        $this->em = $em;
        $this->language = $language;
        $this->user = $user;
    }
    /**
     * @return EntityManager
     */
    public function getEntityManager() : EntityManager
    {
        return $this->em;
    }
    /**
     * @return Language
     */
    public function getLanguage() : Language
    {
        return $this->language;
    }
    /**
     * @return User
     */
    public function getUser() : User
    {
        return $this->user;
    }
}