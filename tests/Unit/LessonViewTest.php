<?php

namespace TestLibrary\Unit;

use LearningMetadata\Business\ViewModel\Lesson\LessonView;
use TestLibrary\ContainerAwareTest;

class LessonViewTest extends ContainerAwareTest
{
    /**
     * @expectedException \RuntimeException
     */
    public function test_lesson_view_name_failure()
    {
        $data = [
            'name' => '',
            'tips' => [
                'Tip 1',
                'Tip 2',
                'Tip 3',
            ],
            'lessonTexts' => [
                'Lesson text 1',
                'Lesson text 2',
                'Lesson text 3',
            ]
        ];

        $deserializer = $this->container->get('library.deserializer');

        $deserializer->create($data, LessonView::class);
    }
    /**
     * @expectedException \RuntimeException
     */
    public function test_lesson_view_lesson_texts_failure()
    {
        $data = [
            'name' => 'Some name',
            'tips' => [
                'Tip 1',
                'Tip 2',
                'Tip 3',
            ],
            'lessonTexts' => []
        ];

        $deserializer = $this->container->get('library.deserializer');

        $deserializer->create($data, LessonView::class);
    }

    public function test_lesson_view()
    {
        $data = [
            'name' => 'Some name',
            'tips' => [
                'Tip 1',
                'Tip 2',
                'Tip 3',
            ],
            'lessonTexts' => [
                'Lesson text 1',
                'Lesson text 2',
                'Lesson text 3',
            ],
        ];

        $deserializer = $this->container->get('library.deserializer');

        /** @var LessonView $object */
        $object = $deserializer->create($data, LessonView::class);

        static::assertEquals('Some name', $object->getName());
        static::assertInternalType('array', $object->getTips());
        static::assertInternalType('array', $object->getLessonTexts());

        static::assertNotEmpty($object->getTips());
        static::assertNotEmpty($object->getLessonTexts());
    }
}