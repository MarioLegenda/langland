<?php

namespace API\SharedDataBundle\Controller;

use API\SharedDataBundle\Entity\Course;
use API\SharedDataBundle\Repository\Status;
use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Controller\MasterSecurityController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseController extends MasterSecurityController implements AdminAuthInterface
{
    public function createAction(Request $request)
    {
        $course = Course::createFromRequest($request->request);
        $response = $this->get('app.manual_validator')->validate($course);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        $courseRepo = $this->get('api.shared.course_repository');
        $data = $request->request->all();

        $resultResolver = $courseRepo->findCourseByName($data);

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            return $this->createFailedJsonResponse(array(
                sprintf('Course \'%s\' already exists', $data['name']),
            ));
        }

        $resultResolver = $courseRepo->create(array(
            'name' => $data['name'],
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array(
                sprintf('Course %s could not be created', $data['name'])
            ));
        }

        return $this->createSuccessJsonResponse(array(
            'id' => $resultResolver->getData()['create_course']['last_insert_id'],
        ));
    }

    public function getAllAction(Request $request)
    {
        $courseRepo = $this->get('api.shared.course_repository');
        $data = $request->request->all();

        $resultResolver = $courseRepo->findAllByLanguage($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createSuccessJsonResponse(array(
                'courses' => array(),
            ));
        }

        $data = $resultResolver->getData();

        if (is_string(array_keys($data)[0])) {
            $data = array($data);
        }

        return $this->createSuccessJsonResponse(array(
            'courses' => $data,
        ));
    }

    public function getInitialCourseInfoAction(Request $request)
    {
        $courseRepo = $this->get('api.shared.course_repository');

        $resultResolver = $courseRepo->getInitialCourseInfo($request->request->all());

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array('Course not found'));
        }

        return $this->createSuccessJsonResponse($resultResolver->getData());
    }
}
