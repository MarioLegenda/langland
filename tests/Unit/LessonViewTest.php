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
            'lessonOrder' => 1,
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

        static::assertTrue($deserializer->hasErrors());
        static::assertInternalType('object', $deserializer->getSerializedObject());

        if ($errors = $deserializer->getErrorsString()) {
            throw new \RuntimeException($errors);
        }
    }
    /**
     * @expectedException \RuntimeException
     */
    public function test_lesson_view_learning_order_failure()
    {
        $data = [
            'lessonOrder' => null,
            'name' => 'Mile',
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

        static::assertTrue($deserializer->hasErrors());
        static::assertInternalType('object', $deserializer->getSerializedObject());

        if ($errors = $deserializer->getErrorsString()) {
            throw new \RuntimeException($errors);
        }
    }
    /**
     * @expectedException \RuntimeException
     */
    public function test_lesson_view_lesson_texts_failure()
    {
        $data = [
            'learningOrder' => 1,
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

        static::assertTrue($deserializer->hasErrors());
        static::assertInternalType('object', $deserializer->getSerializedObject());

        if ($errors = $deserializer->getErrorsString()) {
            throw new \RuntimeException($errors);
        }
    }

    public function test_lesson_view()
    {
        $data = [
            'learningOrder' => 1,
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

        $deserializer->create($data, LessonView::class);
        /** @var LessonView $object */
        $object = $deserializer->getSerializedObject();

        static::assertFalse($deserializer->hasErrors());
        static::assertEquals('Some name', $object->getName());
        static::assertInternalType('array', $object->getTips());
        static::assertInternalType('array', $object->getLessonTexts());
        static::assertInternalType('int', $object->getLearningOrder());

        static::assertNotEmpty($object->getTips());
        static::assertNotEmpty($object->getLessonTexts());
    }
}