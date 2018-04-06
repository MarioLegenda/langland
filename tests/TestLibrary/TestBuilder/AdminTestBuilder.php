<?php

namespace TestLibrary\TestBuilder;

use AdminBundle\Command\Helper\FakerTrait;
use AdminBundle\Entity\Language;
use PublicApi\Infrastructure\Type\CourseType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;
use Tests\TestLibrary\DataProvider\CourseDataProvider;
use Tests\TestLibrary\DataProvider\LessonDataProvider;
use TestLibrary\DataProvider\WordDataProvider;

class AdminTestBuilder
{
    use FakerTrait;

    /**
     * @var ContainerInterface $container
     */
    private $container;
    /**
     * @var LanguageDataProvider
     */
    private $languageDataProvider;
    /**
     * @var CourseDataProvider
     */
    private $courseDataProvider;
    /**
     * @var LessonDataProvider
     */
    private $lessonDataProvider;
    /**
     * @var WordDataProvider
     */
    private $wordDataProvider;
    /**
     * AdminTestBuilder constructor.
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;

        $this->languageDataProvider = $this->container->get('data_provider.language');
        $this->courseDataProvider = $this->container->get('data_provider.course');
        $this->lessonDataProvider = $this->container->get('data_provider.lesson');
        $this->wordDataProvider = $this->container->get('data_provider.word');
    }
    /**
     * @return Language
     */
    public function buildAdmin(): Language
    {
        $language = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $types = ['Beginner', 'Intermediate', 'Advanced'];

        $courses = [];

        foreach ($types as $key => $type) {
            $courses[] = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language, [
                'courseOrder' => $key,
                'type' => CourseType::fromValue($type),
            ]);
        }

        $lessons = [];
        foreach ($courses as $course) {
            for ($i = 0; $i < 5; $i++) {
                $lessons[] = $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course, [
                    'learningOrder' => $i,
                ]);
            }
        }

        foreach ($lessons as $lesson) {
            for ($i = 0; $i < 5; $i++) {
                for ($a = 0; $a < 5; $a++) {
                    $this->wordDataProvider->createWithLesson(
                        $this->getFaker(),
                        $language,
                        $lesson,
                        ['level' => $a+1]
                    );
                }
            }
        }

        for ($i = 0; $i < 5; $i++) {
            $this->createWords(30, $language, [
                'level' => $i,
            ]);
        }

        return $language;
    }
    /**
     * @param int $numOfWords
     * @param Language $language
     * @param array $seedData
     */
    private function createWords(
        int $numOfWords,
        Language $language,
        array $seedData
    ) {
        for ($i = 0; $i < $numOfWords; $i++) {
            $this->wordDataProvider->createDefaultDb($this->getFaker(), $language, $seedData);
        }
    }
}