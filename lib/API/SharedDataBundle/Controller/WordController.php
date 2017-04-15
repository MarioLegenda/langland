<?php

namespace API\SharedDataBundle\Controller;

use API\SharedDataBundle\Entity\Word;
use API\SharedDataBundle\Repository\Status;
use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Controller\MasterSecurityController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WordController extends MasterSecurityController implements AdminAuthInterface
{
    public function createAction(Request $request)
    {
        $wordRepo = $this->get('api.shared.word_repository');
        $data = $request->request->all();

        $resultResolver = $wordRepo->findWordByWord(array(
            'word' => $data['word'],
        ));

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            return $this->createFailedJsonResponse(array(
                sprintf('Word \'%s\' already exists', $data['word']),
            ));
        }

        $resultResolver = $wordRepo->create($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createInternalErrorJsonResponse(array(
                sprintf('Creating word %s failed', $data['word']),
            ));
        }

        return $this->createSuccessJsonResponse();
    }

    public function scheduleRemovalAction(Request $request)
    {
        $wordRepo = $this->get('api.shared.word_repository');
        $wordId = $request->request->get('word_id');

        $resultResolver = $wordRepo->findWordById(array(
            'word_id' => $wordId,
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array(
                sprintf('Could not find word with id %s', $wordId),
            ));
        }

        $resultResolver = $wordRepo->scheduleRemoval(array(
            'word_id' => $resultResolver->getData()['id'],
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array(
                'Something went wrong in scheduling removal of a word'
            ));
        }

        return $this->createSuccessJsonResponse();
    }

    public function removeAction(Request $request)
    {
        $wordRepo = $this->get('api.shared.word_repository');
        $wordId = $request->request->get('word_id');

        $resultResolver = $wordRepo->remove(array(
            'word_id' => $wordId,
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse();
    }
}
