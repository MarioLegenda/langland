<?php

namespace AdminBundle\Command;

use AdminBundle\Command\Helper\CategoryFactory;
use AdminBundle\Command\Helper\CourseFactory;
use AdminBundle\Command\Helper\LanguageFactory;
use AdminBundle\Command\Helper\LanguageInfoFactory;
use AdminBundle\Command\Helper\LessonFactory;
use AdminBundle\Command\Helper\QuestionFactory;
use AdminBundle\Command\Helper\WordFactory;
use AdminBundle\Command\Helper\WordTranslationFactory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

require_once __DIR__.'/../../../vendor/fzaninotto/faker/src/autoload.php';

class SeedCommand extends ContainerAwareCommand
{
    /**
     * @void
     */
    public function configure()
    {
        $this
            ->setName('langland:learning_metadata:seed')
            ->addOption('words', 'w', InputOption::VALUE_OPTIONAL, null, 10)
            ->addOption('lessons', 'l', InputOption::VALUE_OPTIONAL, null, 5)
            ->setDescription('Seeds initial data');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->isValidEnvironment();

        $numOfWords = (int) $input->getOption('words');
        $numOfLessons = (int) $input->getOption('lessons');

        $em = $this->getContainer()->get('doctrine')->getManager();

        $faker = \Faker\Factory::create();

        $languages = array('French', 'Spanish', 'Italian');
        $categories = array('nature', 'body', 'soul', 'love');

        $languageFactory = new LanguageFactory($em);
        $categoryFactory = new CategoryFactory($em);
        $wordTranslationFactory = new WordTranslationFactory();
        $wordFactory = new WordFactory($em);
        $languageInfoFactory = new LanguageInfoFactory($em);
        $courseFactory = new CourseFactory($em);
        $lessonFactory = new LessonFactory($em);
        $questionsFactory = new QuestionFactory($em);

        $questionsFactory->create();
        $categoryFactory->create($categories, true);
        $languageObjects = $languageFactory->create($languages, true);
        $wordObjects = [];

        foreach ($languageObjects as $i => $languageObject) {
            $words = $wordFactory->create(
                $categoryFactory,
                $wordTranslationFactory,
                $languageObject,
                $numOfWords
            );

            $wordObjects = array_merge($wordObjects, $words);
        }

        foreach ($languageObjects as $i => $languageObject) {
            $languageInfoFactory->create($languageObject);

            $courseFactory->create($languageObject, 3);
        }

        $courses = $this->getContainer()->get('learning_metadata.repository.course')->findAll();

        $lessons = [];
        foreach ($courses as $course) {
            $lessons = $lessonFactory->create($course, $numOfLessons);
        }

        if (empty($lessons)) {
            throw new \RuntimeException('Seeding went wrong. There are no created lessons');
        }

        foreach ($wordObjects as $key => $word) {
            if (($key % 2) === 0) {
                $lesson = $lessons[array_rand($lessons, 1)];

                $word->setLesson($lesson);
                $em->persist($word);
            }
        }

        $em->flush();
    }
    /**
     * @throws \RuntimeException
     */
    private function isValidEnvironment()
    {
        $env = $this->getContainer()->get('kernel')->getEnvironment();
        $validEnvironments = ['dev', 'test'];

        if (!in_array($env, $validEnvironments)) {
            $message = sprintf('This command can only be executed in \'%s\' environments', implode(', ', $validEnvironments));

            throw new \RuntimeException($message);
        }
    }
}
