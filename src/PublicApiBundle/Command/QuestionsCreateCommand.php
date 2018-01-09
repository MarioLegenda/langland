<?php

namespace PublicApiBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use LearningSystem\Infrastructure\Questions;
use PublicApiBundle\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QuestionsCreateCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this
            ->setName('common:create_questions');
    }
    /**
     * @inheritdoc
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $this->removeQuestions($em, $output);
        $this->createQuestions($em, $output);
    }
    /**
     * @param EntityManagerInterface $em
     * @param OutputInterface $output
     */
    private function createQuestions(
        EntityManagerInterface $em,
        OutputInterface $output
    ) {
        $output->writeln('');
        $output->writeln('<info>Creating questions</info>');
        $output->writeln('');

        $questions = new Questions();
        foreach ($questions->getQuestions() as $type => $question) {
            $q = new Question();
            $q
                ->setName($question['name'])
                ->setQuestion($question['question'])
                ->setAnswers($question['answers']);

            $em->persist($q);
        }

        $em->flush();

        $output->writeln('');
        $output->writeln('<info>Questions created</info>');
        $output->writeln('');
    }
    /**
     * @param EntityManagerInterface $em
     * @param OutputInterface $output
     */
    private function removeQuestions(
        EntityManagerInterface $em,
        OutputInterface $output
    ) {
        $output->writeln('');
        $output->writeln('<info>Removing previous questions</info>');
        $output->writeln('');

        $questions = $em->getRepository(Question::class)->findAll();

        foreach ($questions as $question) {
            $em->remove($question);
        }

        $em->flush();

        $output->writeln('');
        $output->writeln('<info>Previous questions removed</info>');
        $output->writeln('');
    }
}