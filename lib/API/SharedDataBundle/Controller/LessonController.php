<?php

namespace API\SharedDataBundle\Controller;

use API\SharedDataBundle\Entity\Lesson;
use API\SharedDataBundle\Repository\Status;
use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Controller\MasterSecurityController;
use BlueDot\Entity\PromiseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class LessonController extends MasterSecurityController implements AdminAuthInterface
{
    public function createAction(Request $request)
    {
        $lesson = Lesson::createFromRequest($request->request);

        $response = $this->get('app.manual_validator')->validate($lesson);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        $lessonRepo = $this->get('api.shared.lesson_repository');
        $data = $request->request->all();

        $resultResolver = $lessonRepo->findLessonByClass($data);

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            return $this->createFailedJsonResponse(array(
                sprintf('Lesson \'%s\' already exists', $data['name']),
            ));
        }

        $resultResolver = $lessonRepo->create($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array(
                sprintf('Lesson %s could not be created', $data['name'])
            ));
        }

        $resultResolver = $lessonRepo->findLessonById(array(
            'lesson_id' => $resultResolver->getData()['last_insert_id'],
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array(
                sprintf('Lesson %s has been created but could not be fetched from the database', $data['name'])
            ));
        }

        return $this->createSuccessJsonResponse(array(
            'created_lesson' => $resultResolver->getData(),
        ));
    }

    public function renameLessonAction(Request $request)
    {
        if (empty($request->request->get('name'))) {
            return $this->createFailedJsonResponse(array(
                'Lesson name cannot be empty',
            ));
        }

        $lessonRepo = $this->get('api.shared.lesson_repository');
        $data = $request->request->all();

        $resultResolver = $lessonRepo->findLessonByClass(array(
            'class_id' => $data['class_id'],
            'name' => $data['name'],
        ));

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            return $this->createFailedJsonResponse(array(
                sprintf('Lesson \'%s\' already exists', $data['name']),
            ));
        }

        $resultResolver = $lessonRepo->renameLesson(array(
            'lesson_id' => $data['lesson_id'],
            'name' => $data['name'],
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array(
                'Lesson could not be renamed'
            ));
        }

        return $this->createSuccessJsonResponse();
    }

    public function findLessonsByClassAction(Request $request)
    {
        $classId = $request->request->get('class_id');

        return $this->get('api.shared.lesson_repository')->findAllByClass($classId)
            ->success(function(PromiseInterface $promise) {
                return $this->createSuccessJsonResponse(array(
                    'lessons' => $promise->getResult()->toArray(),
                ));
            })
            ->failure(function() {
                return $this->createSuccessJsonResponse(array(
                    'lessons' => array(),
                ));
            })
            ->getResult();
    }
}
