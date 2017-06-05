<?php

namespace AdminBundle\Command\Helper;

use AdminBundle\Entity\Game\WordGame;
use AdminBundle\Entity\Game\WordGameUnit;
use Doctrine\ORM\EntityManager;

class WordGameFactory
{
    use FakerTrait;
    /**
     * @var array $wordGames
     */
    private $wordGames;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * WordGameFactory constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param array $lessons
     * @param array $words
     * @return array
     */
    public function create(array $lessons, array $words)
    {
        foreach ($lessons as $lesson) {
            $game = new WordGame();

            $game->setName($this->getFaker()->name);
            $game->setDescription($this->getFaker()->sentence(10));
            $game->setLesson($lesson);

            foreach ($words as $word) {
                $gameUnit = new WordGameUnit();
                $gameUnit->setWord($word);

                $game->addGameUnit($gameUnit);
            }

            $this->em->persist($game);

            $this->wordGames[] = $game;
        }

        $this->em->flush();

        return $this->wordGames;
    }
}