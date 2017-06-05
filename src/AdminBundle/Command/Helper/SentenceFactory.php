<?php

namespace AdminBundle\Command\Helper;

use AdminBundle\Entity\Course;
use Doctrine\ORM\EntityManager;
use AdminBundle\Entity\Sentence;
use AdminBundle\Entity\SentenceTranslation;

class SentenceFactory
{
    use FakerTrait;
    /**
     * @var array $sentenceObjects
     */
    private $sentenceObjects;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * SentenceFactory constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param Course $course
     */
    public function create(Course $course)
    {
        for ($r = 0; $r < 10; $r++) {
            $sentence = new Sentence();
            $sentence->setName($this->getFaker()->name);
            $sentence->setSentence($this->getFaker()->sentence(25));
            $sentence->setCourse($course);

            for ($o = 0; $o < 10; $o++) {
                $sentenceTranslation = new SentenceTranslation();
                $sentenceTranslation->setSentence($this->getFaker()->sentence(25));
                $sentenceTranslation->setMarkedCorrect(0);
                $sentenceTranslation->setName($this->getFaker()->name);

                $sentence->addSentenceTranslation($sentenceTranslation);
            }

            $this->em->persist($sentence);

            $this->sentenceObjects[] = $sentence;
        }

        $this->em->flush();

        return $this->sentenceObjects;
    }
}