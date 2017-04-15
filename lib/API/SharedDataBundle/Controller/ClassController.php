<?php

namespace API\SharedDataBundle\Controller;

use API\SharedDataBundle\Repository\Status;
use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Controller\MasterSecurityController;
use Symfony\Component\HttpFoundation\Request;

class ClassController extends MasterSecurityController implements AdminAuthInterface
{
    public function createAction(Request $request)
    {
        $classRepo = $this->get('api.shared.class_repository');
        $data = $request->request->all();

        $resultResolver = $classRepo->findClassByName($data);

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            return $this->createFailedJsonResponse(array(
                sprintf('Class \'%s\' already exists', $data['name'])
            ));
        }

        $resultResolver = $classRepo->create($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array(
                sprintf('Class %s could not be created', $data['name'])
            ));
        }

        $resultResolver = $classRepo->findClassById(array(
            'class_id' => $resultResolver->getData()['last_insert_id'],
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array(
                sprintf('Class %s is created but could not find it in the database', $data['name'])
            ));
        }

        return $this->createSuccessJsonResponse(array(
            'class' => $resultResolver->getData(),
        ));
    }

    public function updateAction(Request $request)
    {
        if (empty($request->request->get('name'))) {
            return $this->createFailedJsonResponse(array(
                'Class name cannot be empty',
            ));
        }

        $classRepo = $this->get('api.shared.class_repository');
        $data = $request->request->all();

        $resultResolver = $classRepo->findClassByName(array(
            'course_id' => $data['course_id'],
            'name' => $data['name'],
        ));

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            return $this->createFailedJsonResponse(array(
                sprintf('Class with name \'%s\' already exists', $data['name']),
            ));
        }

        $resultResolver = $classRepo->update(array(
            'class_id' => $data['class_id'],
            'name' => $data['name'],
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array(
                sprintf('Updating class %s failed', $data['name'])
            ));
        }

        return $this->createSuccessJsonResponse();
    }

    public function findClassesByCourseAction(Request $request)
    {
        $data = $request->request->all();

        $resultResolver = $this->get('api.shared.class_repository')->findClassesByCourse($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse();
        }

        $data = $resultResolver->getData();
        if (is_string(array_keys($data)[0])) {
            $data = array($data);
        }
        return $this->createSuccessJsonResponse(array(
            'classes' => $data,
        ));
    }
}
