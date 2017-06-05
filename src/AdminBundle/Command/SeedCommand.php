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
use AdminBundle\Entity\Course;
use AdminBundle\Entity\LanguageInfo;
use AdminBundle\Entity\LanguageInfoText;
use AdminBundle\Entity\Lesson;
use AdminBundle\Entity\LessonText;
use AdminBundle\Entity\Sentence;
use AdminBundle\Entity\SentenceTranslation;
use AdminBundle\Entity\SentenceWordPool;
use AdminBundle\Entity\Word;
use Doctrine\Common\Collections\ArrayCollection;
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
            $wordsArray = $wordFactory->create(
                $categoryFactory,
                $wordTranslationFactory,
                $languageObject,
                10
            );

            $languageInfoFactory->create($languageObject);

            $courses = $courseFactory->create($languageObject, 6);

            foreach ($courses as $course) {
                $lessons = $lessonFactory->create($course, 5);
                $sentenceFactory->create($course);
                $wordGameFactory->create($lessons, $wordsArray);
                $questionGameFactory->create($lessons);

/*                for ($t = 0; $t < 5; $t++) {
                    $wordPool = new SentenceWordPool();

                    $wordPool->setName($faker->name);
                    $wordPool->setCourse($course);

                    $poolWord = new ArrayCollection();

                    $count = 0;
                    for (;;) {

                        if ($count === 10) {
                            break;
                        }

                        $poolWord->add($wordsArray[$count]);
                        $count++;
                    }

                    $wordPool->setWords($poolWord);

                    $em->persist($wordPool);
                }*/
            }
        }

        $em->flush();
    }
}
