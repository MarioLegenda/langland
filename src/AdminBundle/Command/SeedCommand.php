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
use AdminBundle\Entity\Course;
use AdminBundle\Entity\Language;
use AdminBundle\Entity\Lesson;
use AdminBundle\Entity\Word;
use Doctrine\ORM\EntityManager;
use Library\Util\Util;
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
            ->addOption('lessons', 'l', InputOption::VALUE_OPTIONAL, null, 10)
            ->setDescription('Seeds initial data');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');

        $this->isValidEnvironment();

        $numOfWords = (int) $input->getOption('words');
        $numOfLessons = (int) $input->getOption('lessons');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        $faker = \Faker\Factory::create();

        $languages = array('French', 'Spanish', 'Italian');
        $categories = array('nature', 'body', 'soul', 'love');

        $languageFactory = new LanguageFactory($em);
        $languageInfoFactory = new LanguageInfoFactory($em);
        $categoryFactory = new CategoryFactory($em);
        $lessonFactory = new LessonFactory($em);
        $wordFactory = new WordFactory($em);
        $wordTranslationFactory = new WordTranslationFactory();
        $courseFactory = new CourseFactory($em);
        $questionFactory = new QuestionFactory($em);

        $languageObjects = $languageFactory->create($languages, true);
        $categoryFactory->create($categories, true);
        $questionFactory->create();

        $levels = [1, 2, 3, 4, 5];

        /** @var Language $languageObject */
        foreach ($languageObjects as $languageObject) {
            $languageInfoFactory->create($languageObject);
            $wordFactory->create(
                $categoryFactory,
                $wordTranslationFactory,
                $languageObject,
                $numOfWords,
                true,
                $levels
            );

            $courseFactory->create($languageObject, true);
            $courses = $courseFactory->getSavedCourses();

            /** @var Course $course */
            foreach ($courses as $course) {
                $lessonFactory->create($course, $numOfLessons, true);
            }

            $limit = 10;
            $offset = 0;

            $savedLessons = $lessonFactory->getSavedLessons();
            if (empty($savedLessons)) {
                throw new \RuntimeException('Lessons cannot be empty');
            }

            foreach ($savedLessons as $lesson) {
                $selectedWords = $this->getWordsByLimitAndOffset(
                    $wordFactory->getSavedWords(),
                    $offset,
                    $limit
                );

                /** @var Word $word */
                foreach ($selectedWords as $word) {
                    $word->setLesson($lesson);
                    $em->persist($word);
                }

                $offset += 10;
                $limit += 10;
            }

            $em->flush();

            $message = sprintf('<info>Finished persisting data patch for %s language</info>', $languageObject->getName());

            $output->writeln($message);

            $lessonFactory->clear();
            $wordFactory->clear();
            $courseFactory->clear();
        }



        $output->writeln('');
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
    /**
     * @param Word[] $words
     * @param int $offset
     * @param int $limit
     * @return Word[]
     */
    public function getWordsByLimitAndOffset(
        array $words,
        int $offset,
        int $limit
    ): array {
        $selectedWords = [];
        for ($i = $offset; $i < $limit; $i++) {
            $selectedWords[$i] = $words[$i];
        }

        return $selectedWords;
    }
}
