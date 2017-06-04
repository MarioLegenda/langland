<?php

namespace AdminBundle\Listener\Custom;

use AdminBundle\Entity\Game\QuestionGame;
use AdminBundle\Entity\Sentence;
use AdminBundle\Event\PostPersistEvent;

class PostPersistListener extends AbstractEntityManagerBaseListener
{
    /**
     * @param PostPersistEvent $event
     */
    public function onPostPersist(PostPersistEvent $event)
    {
        if (array_key_exists('sentence', $event->getEntities())) {
            $this->handleSentenceJob($event->getEntities()['sentence']);
        }

        if (array_key_exists('question', $event->getEntities())) {
            $this->handleQuestionJob($event->getEntities()['question']);
        }
    }

    private function handleQuestionJob(QuestionGame $game)
    {
        $dbAnswers = $this->em->getRepository('AdminBundle:Game\QuestionGameAnswer')->findBy(array(
            'question' => $game,
        ));

        foreach ($dbAnswers as $answer) {
            if (!$game->hasAnswer($answer)) {
                $this->em->remove($answer);
            }
        }
    }

    private function handleSentenceJob(Sentence $sentence)
    {

    }
}