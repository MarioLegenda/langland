<?php

namespace PublicApiBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use PublicApiBundle\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QuestionsCreateCommand extends ContainerAwareCommand
{
    private $questions = [
        'speaking_languages' => [
            'question' => 'Except your native language, how many languages do you already speak?',
            'name' => 'speaking_languages',
            'answers' => [],
        ],
        'profession' => [
            'question' => 'In what field do you currently work in?',
            'name' => 'profession',
            'answers' => [
                'arts_and_entertainment' => 'Arts and entertainment',
                'business' => 'Business',
                'industrial_and_manufacturing' => 'Industrial and manufacturing',
                'law_enforcement_and_armed_forces' => 'Law Enforcement and Armed Forces',
                'science_and_technology' => 'Science and technology',
                'healthcare_and_medicine' => 'Healthcare and medicine',
                'service_oriented_occupation' => 'Service oriented occupation',
            ],
        ],
        'person_type' => [
            'question' => 'Are you a risk taker or a \'sure thing\' person?',
            'name' => 'person_type',
            'answers' => [
                'risk_taker' => 'Risk taker',
                'sure_thing' => '\'Sure thing\' person'
            ],
        ],
        'learning_time' => [
            'question' => 'What is the best time of day for you to learn?',
            'name' => 'learning_time',
            'answers' => [
                'morning' => 'Morning',
                'evening' => 'Evening',
                'early_afternoon' => 'Early afternoon',
                'late_afternoon' => 'Late afternoon',
                'night' => 'Night'
            ],
        ],
        'free_time' => [
            'question' => 'How much free time do you have in a day?',
            'name' => 'free_time',
            'answers' => [
                '30_minutes' => '30 minutes',
                '1_hour' => '1 hour',
                '1_hour_and_a_half' => '1 hour and a half',
                '2_hours' => '2 hours',
                'all_time' => 'I\'ve got all the time in the world',
            ],
        ],
        'memory' => [
            'question' => 'Would you say that you have a better short term or long term memory? Or is it something in between?',
            'name' => 'memory',
            'answers' => [
                'short_term' => 'Short term memory',
                'long_term' => 'Long term memory',
                'in_between' => 'Somewhere in between',
            ],
        ],
        'challenges' => [
            'question' => 'Do you embrace challenges?',
            'name' => 'challenges',
            'answers' => [
                'likes_challenges' => 'I embrace challenges',
                'dislike_challenges' => 'I don\'t like challenges',
            ],
        ],
        'stressful_job' => [
            'question' => 'Is your job stressful?',
            'name' => 'stressful_job',
            'answers' => [
                'stressful_job' => 'Yes',
                'nonstressful_job' => 'No',
            ],
        ],
    ];
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
        foreach ($this->questions as $type => $question) {
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