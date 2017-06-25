<?php

namespace AdminBundle\Command\Helper;

use AdminBundle\Entity\Game\QuestionGame;
use AdminBundle\Entity\Game\QuestionGameAnswer;
use Doctrine\ORM\EntityManager;

class QuestionGameFactory
{
    use FakerTrait;
    /**
     * @var array $games
     */
    private $games;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * QuestionGameFactory constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param array $lessons
     * @return array
     */
    public function create(array $lessons)
    {
        foreach ($lessons as $lesson) {
            $game = new QuestionGame();
            $game->setName($this->getFaker()->name);
            $game->setUrl(\URLify::filter($game->getName()));
            $game->setDescription($this->getFaker()->sentence(150));
            $game->setMaxTime(20);
            $game->setHasTimeLimit(true);

            $game->setLesson($lesson);

            for ($i = 0; $i < 5; $i++) {
                $answer = new QuestionGameAnswer();
                $answer->setName($this->getFaker()->name);
                $answer->setIsCorrect(($i === 0) ? true : false);

                $game->addAnswer($answer);
            }

            $this->em->persist($game);

            $this->games[] = $game;
        }

        $this->em->flush();

        return $this->games;
    }
}
