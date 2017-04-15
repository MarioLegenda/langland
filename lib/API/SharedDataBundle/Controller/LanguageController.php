<?php

namespace API\SharedDataBundle\Controller;

use API\SharedDataBundle\Helper\ParameterFinder;
use API\SharedDataBundle\Repository\Status;
use API\SharedDataBundle\Controller\MasterSecurityController as SharedDataMasterSecurityController;

class LanguageController extends SharedDataMasterSecurityController
{
    public function createAction()
    {
        $request = $this->get('request');
        $languageRepo = $this->get('api.shared.language_repository');
        $data = array();

        $found = ParameterFinder::findSingleParameter($request, 'language');

        if (empty($found)) {
            return $this->createInternalErrorJsonResponse(array(
                sprintf('\'%s\' field not found in the request', 'language')
            ));
        }

        $data['language'] = $found;

        $resultResolver = $languageRepo->findLanguageByLanguage($data);

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            return $this->createFailedJsonResponse(array(
                'Language already exists',
            ));
        }

        $resultResolver = $languageRepo->create($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createInternalErrorJsonResponse(array(
                sprintf('Language %s could not be created', $data['language'])
            ));
        }

        return $this->createSuccessJsonResponse(array(
            'language' => array(
                'language' => $data['language'],
                'id' => $resultResolver->getData()['last_insert_id'],
            ),
        ));
    }

    public function editAction()
    {
        $request = $this->get('request');
        $languageRepo = $this->get('api.shared.language_repository');
        $data = array();

        $found = ParameterFinder::findParameters($request, array('id', 'language'));

        if (empty($found)) {
            return $this->createInternalErrorJsonResponse(array(
                sprintf('\'%s\' field not found in the request', 'language')
            ));
        }

        $data['language'] = $found['language'];
        $data['language_id'] = $found['id'];

        $resultResolver = $languageRepo->updateLanguageName($data);

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            return $this->createFailedJsonResponse(array(
                'Language already exists',
            ));
        }

        $resultResolver = $languageRepo->findLanguageById(array(
            'language_id' => $data['language_id'],
        ));

        return $this->createSuccessJsonResponse(array(
            'language' => array(
                'language' => $data['language'],
                'id' => $resultResolver->getData()['last_insert_id'],
            ),
        ));
    }

    public function findLanguageByIdAction()
    {
        $request = $this->get('request');
        $languageRepo = $this->get('api.shared.language_repository');
        $data = array();

        $found = ParameterFinder::findSingleParameter($request, 'id');

        if (empty($found)) {
            return $this->createInternalErrorJsonResponse(array(
                sprintf('\'%s\' field not found in the request', 'id')
            ));
        }

        $data['language_id'] = $found;

        $resultResolver = $languageRepo->findLanguageById($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse(array(
            'language' => $resultResolver->getData(),
        ));
    }

    public function findAllAction()
    {
        $resultResolver = $this->get('api.shared.language_repository')->findAll();

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createSuccessJsonResponse();
        }

        $data = $resultResolver->getData();
        if (is_string(array_keys($data)[0])) {
            $data = array($data);
        }

        return $this->createSuccessJsonResponse(array(
            'languages' => $data,
        ));
    }

    public function updateWorkingLanguageAction()
    {
        $request = $this->get('request');
        $data = $request->request->all();

        $resultResolver = $this->get('api.shared.language_repository')->updateWorkingLanguage($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array(
                'Something went wrong. Please, refresh the page and try again'
            ));
        }

        return $this->createSuccessJsonResponse();
    }
}
