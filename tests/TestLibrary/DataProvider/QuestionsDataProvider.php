<?php

namespace TestLibrary\DataProvider;

use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;
use Tests\TestLibrary\DataProvider\DefaultDataProviderInterface;
use LearningSystem\Infrastructure\Questions;
use PublicApiBundle\Entity\Question;

class QuestionsDataProvider implements DefaultDataProviderInterface
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;
    /**
     * QuestionsDataProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @param Generator $faker
     */
    public function createDefaultDb(Generator $faker)
    {
        $questions = $this->createDefault($faker);

        foreach ($questions as $question) {
            $this->em->persist($question);
        }

        $this->em->flush();
    }
    /**
     * @param Generator $faker
     * @return Question[]
     */
    public function createDefault(Generator $faker): array
    {
        $questions = new Questions();
        $array = [];
        foreach ($questions->getQuestions() as $type => $question) {
            $q = new Question();
            $q
                ->setName($question['name'])
                ->setQuestion($question['question'])
                ->setAnswers($question['answers']);

            $array[] = $q;
        }

        return $array;
    }
}