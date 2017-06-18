<?php

namespace AdminBundle\Command;

use AdminBundle\Command\Helper\CategoryFactory;
use AdminBundle\Command\Helper\CourseFactory;
use AdminBundle\Command\Helper\LanguageFactory;
use AdminBundle\Command\Helper\LanguageInfoFactory;
use AdminBundle\Command\Helper\LessonFactory;
use AdminBundle\Command\Helper\QuestionGameFactory;
use AdminBundle\Command\Helper\SentenceFactory;
use AdminBundle\Command\Helper\WordFactory;
use AdminBundle\Command\Helper\WordGameFactory;
use AdminBundle\Command\Helper\WordTranslationFactory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require_once __DIR__.'/../../../vendor/fzaninotto/faker/src/autoload.php';

class SeedCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('langland:seed')
            ->setDescription('Seeds initial data');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $faker = \Faker\Factory::create();

        $languages = array('french', 'spanish', 'italian');
        $categories = array('nature', 'body', 'soul', 'love');


        $languageFactory = new LanguageFactory($em);
        $categoryFactory = new CategoryFactory($em);
        $wordTranslationFactory = new WordTranslationFactory();
        $wordFactory = new WordFactory($em);
        $languageInfoFactory = new LanguageInfoFactory($em);
        $courseFactory = new CourseFactory($em);
        $lessonFactory = new LessonFactory($em);
        $sentenceFactory = new SentenceFactory($em);
        $wordGameFactory = new WordGameFactory($em);
        $questionGameFactory = new QuestionGameFactory($em);

        $categoryFactory->create($categories, true);
        $languageObjects = $languageFactory->create($languages, true);

        foreach ($languageObjects as $i => $languageObject) {
            $wordFactory->create(
                $categoryFactory,
                $wordTranslationFactory,
                $languageObject,
                10
            );
        }

        foreach ($languageObjects as $i => $languageObject) {
            $languageInfoFactory->create($languageObject);

            $courseFactory->create($languageObject, 6);
        }

        $courses = $this->getContainer()->get('doctrine')->getRepository('AdminBundle:Course')->findAll();

        foreach ($courses as $course) {
            $lessonFactory->create($course, 10);

            $sentenceFactory->create($course);
        }

        foreach ($courses as $course) {
            $lessons = $this->getContainer()->get('doctrine')->getRepository('AdminBundle:Lesson')->findBy(array(
                'course' => $course,
            ));

            $words = $this->getContainer()->get('doctrine')->getRepository('AdminBundle:Word')->findBy(array(
                'language' => $course->getLanguage(),
            ));

            $wordGameFactory->create($lessons, $words);
            $questionGameFactory->create($lessons);
        }
    }
}
