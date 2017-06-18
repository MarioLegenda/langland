<?php

namespace AdminBundle\Helper;

use AdminBundle\Entity\Game\WordGame;
use AdminBundle\Entity\Game\WordGameUnit;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class GameEntityCreator
{
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * GameEntityCreator constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param Request $request
     * @return WordGame
     */
    public function createFromRequest(Request $request) : WordGame
    {
        $game = new WordGame();
        $request = $request->request;

        if (!$request->has('game')) {
            return $game;
        }

        $data = $request->get('game');

        if (array_key_exists('name', $data)) {
            $game->setName($data['name']);
        }

        if (array_key_exists('description', $data)) {
            $game->setDescription($data['description']);
        }

        if (array_key_exists('lesson', $data)) {
            $lesson = $this->em->getRepository('AdminBundle:Lesson')->find($data['lesson']);

            $game->setLesson($lesson);
        }

        if (array_key_exists('maxTime', $data)) {
            if (!empty($data['maxTime'])) {
                $game->setMaxTime($data['maxTime']);
            }
        }

        if (array_key_exists('words', $data)) {
            $ids = array();
            foreach ($data['words'] as $word) {
                $ids[] = $word['id'];
            }

            $words = $this->em->getRepository('AdminBundle:Word')->findMultipleById($ids);

            foreach ($words as $word) {
                $gameUnit = new WordGameUnit();
                $gameUnit->setWord($word);

                $game->addGameUnit($gameUnit);
            }
        }

        return $game;
    }
}