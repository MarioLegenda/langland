<?php

namespace AdminBundle\Command;

use AdminBundle\Entity\Category;
use AdminBundle\Entity\Course;
use AdminBundle\Entity\Language;
use AdminBundle\Entity\LanguageInfo;
use AdminBundle\Entity\LanguageInfoText;
use AdminBundle\Entity\Lesson;
use AdminBundle\Entity\Sentence;
use AdminBundle\Entity\SentenceTranslation;
use AdminBundle\Entity\SentenceWordPool;
use AdminBundle\Entity\Word;
use AdminBundle\Entity\Image;
use ArmorBundle\Entity\User;
use BlueDot\BlueDotInterface;
use BlueDot\Entity\PromiseInterface;
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
        $courses = array('I know this ...');

        $categoryObjects = array();

        foreach ($categories as $cat) {
            $category = new Category();
            $category->setName($cat);

            $categoryObjects[] = $category;

            $em->persist($category);
            $em->flush();
        }

        for ($i = 0; $i < count($languages); $i++) {
            $lang = $languages[$i];

            $language = new Language();
            $language->setName($lang);
            $language->setShowOnPage(true);
            $language->setListDescription($faker->sentence(60));

            $em->persist($language);

            $em->flush();

            $wordsArray = array();
            for ($m = 0; $m < 10; $m++) {
                $word = new Word();
                $word->setName($faker->word);
                $word->setLanguage($language);

                $categoryCollection = new ArrayCollection();
                $categoryCollection->add($categoryObjects[$i]);
                $categoryCollection->add($categoryObjects[$i + 1]);

                $word->setCategories($categoryCollection);
                $word->setDescription($faker->sentence(60));
                $word->setType($faker->company);

                $em->persist($word);

                $wordsArray[] = $word;
            }

            for ($q = 0; $q < 10; $q++) {
                $languageInfo = new LanguageInfo();
                $languageInfo->setLanguage($language);
                $languageInfo->setName($faker->word);

                for ($s = 0; $s < 10; $s++) {
                    $text = new LanguageInfoText();
                    $text->setName($faker->text(500));

                    $text->setLanguageInfo($languageInfo);

                    $languageInfo->addLanguageInfoText($text);
                }

                $em->persist($languageInfo);
            }

            $em->flush();

            $course = new Course();
            $course->setName($courses[0]);
            $course->setLanguage($language);

            $em->persist($course);

            for ($a = 0; $a < 5; $a++) {
                $lesson = new Lesson();
                $lesson->setName($faker->name);
                $lesson->setCourse($course);

                $em->persist($lesson);
            }

            $em->flush();

            for ($r = 0; $r < 10; $r++) {
                $sentence = new Sentence();
                $sentence->setName($faker->name);
                $sentence->setSentence($faker->sentence(25));
                $sentence->setCourse($course);

                for ($o = 0; $o < 10; $o++) {
                    $sentenceTranslation = new SentenceTranslation();
                    $sentenceTranslation->setSentence($faker->sentence(25));
                    $sentenceTranslation->setMarkedCorrect(0);
                    $sentenceTranslation->setName($faker->name);

                    $sentence->addSentenceTranslation($sentenceTranslation);
                }

                $em->persist($sentence);
            }

            for ($t = 0; $t < 5; $t++) {
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
            }
        }

        $em->flush();
    }
}
