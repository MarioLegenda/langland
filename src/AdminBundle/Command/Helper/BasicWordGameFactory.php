<?php

namespace AdminBundle\Command\Helper;

use AdminBundle\Entity\BasicWordGame;
use AdminBundle\Entity\Lesson;
use Doctrine\ORM\EntityManager;

class BasicWordGameFactory
{
    use FakerTrait;
    /**
     * @var array $categoryObjects
     */
    private $gameObjects;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * CategoryFactory constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param Lesson $lesson
     * @param int $numOfEntries
     * @return array
     */
    public function create(Lesson $lesson, int $numOfEntries = 5) : array
    {
        for ($i = 0; $i < $numOfEntries; $i++) {
            $gameObject = new BasicWordGame();
            $gameObject->setName($this->getFaker()->name);
            $gameObject->setLesson($lesson);

            $lesson->addBasicWordGame($gameObject);

            $this->gameObjects[] = $gameObject;
        }

        $this->em->persist($lesson);

        $this->em->flush();

        return $this->gameObjects;
    }
}