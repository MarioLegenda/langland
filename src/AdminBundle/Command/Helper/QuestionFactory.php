<?php

namespace AdminBundle\Command\Helper;

use Doctrine\ORM\EntityManagerInterface;
use LearningSystem\Infrastructure\Questions;
use PublicApiBundle\Entity\Question;

class QuestionFactory
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;
    /**
     * QuestionFactory constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @void
     */
    public function create()
    {
        $questions = new Questions();
        foreach ($questions->getQuestions() as $type => $question) {
            $q = new Question();
            $q
                ->setName($question['name'])
                ->setQuestion($question['question'])
                ->setAnswers($question['answers']);

            $this->em->persist($q);
        }

        $this->em->flush();
    }
}