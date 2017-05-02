<?php

namespace API\SharedDataBundle\Controller;

use API\SharedDataBundle\Repository\Status;
use API\SharedDataBundle\Repository\TheoryRepository;
use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Controller\MasterSecurityController;
use BlueDot\Entity\PromiseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TheoryController extends MasterSecurityController implements AdminAuthInterface
{
    public function createAction(Request $request)
    {
        $theory = TheoryRepository::createTheoryEntity($request);

        $response = $this->get('app.manual_validator')->validate($theory);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        $theoryRepo = $this->get('api.shared.theory_repository');
        $data = $request->request->all();

        $resultResolver = $theoryRepo->findTheoryByName($data);

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            return $this->createFailedJsonResponse(array(
                sprintf('Theory with name \'%s\' already exists', $data['name']),
            ));
        }

        $resultResolver = $theoryRepo->create($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array(
                sprintf('Could not create %s theory', $data['name']),
            ));
        }

        $resultResolver = $theoryRepo->findTheoryById(array(
            'id' => $resultResolver->getData()['last_insert_id'],
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array(
                sprintf('Theory %s was created but it could not be fetched from the database', $data['name']),
            ));
        }

        return $this->createSuccessJsonResponse(array(
            'created_theories' => $resultResolver->getData(),
        ));
    }

    public function renameAction(Request $request)
    {
        if (empty($request->request->get('name'))) {
            return $this->createFailedJsonResponse(array(
                'Theory name cannot be empty',
            ));
        }

        $theoryRepo = $this->get('api.shared.theory_repository');
        $data = $request->request->all();

        $resultResolver = $theoryRepo->findTheoryByName(array(
            'lesson_id' => $data['lesson_id'],
            'name' => $data['name'],
        ));

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            return $this->createFailedJsonResponse(array(
                sprintf('Theory with name \'%s\' already exists', $data['name']),
            ));
        }

        $resultResolver = $theoryRepo->rename(array(
            'theory_id' => $data['theory_id'],
            'name' => $data['name'],
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array(
                sprintf('Could not rename %s theory', $data['name']),
            ));
        }

        return $this->createSuccessJsonResponse();
    }

    public function createTheoryDeckAction(Request $request)
    {
        $theoryRepo = $this->get('api.shared.theory_repository');
        $deck = $theoryRepo->createTheoryDeckEntity($request);

        $response = $this->get('app.manual_validator')->validate($deck);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        if ($deck->getId() !== null) {
            $promise = $theoryRepo->updateTheoryDeck($deck);

            if ($promise->isSuccess()) {
                return $this->createSuccessJsonResponse(array(
                    'deck_id' => $deck->getId(),
                ));
            }

            if ($promise->isFailure()) {
                return $this->createFailedJsonResponse(array(
                    'Deck failed to create',
                ));
            }
        }

        $resultResolver = $theoryRepo->findDeckByInternalName(array(
            'internal_name' => $deck->getInternalName(),
            'theory_id' => $deck->getTheoryId(),
        ));

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            return $this->createFailedJsonResponse(array(
                sprintf('Deck with internal name \'%s\' already exists', $deck->getInternalName())
            ));
        }

        return $theoryRepo->createTheoryDeck($deck)
            ->success(function(PromiseInterface $promise) {
                return $this->createSuccessJsonResponse(array(
                    'deck_id' => $promise->getResult()->get('create_theory_deck')->get('last_insert_id'),
                ));
            })
            ->failure(function() {
                return $this->createFailedJsonResponse(array(
                    'Deck failed to create',
                ));
            })
            ->getResult();
    }

    public function findAllDecksByTheoryAction(Request $request)
    {
        $data = $request->request->all();
        $theoryRepo = $this->get('api.shared.theory_repository');

        $resultResolver = $theoryRepo->findDecksByTheory($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createSuccessJsonResponse(array(
                'selection_decks' => array(),
            ));
        }

        $data = $resultResolver->getData();

        if (is_string(array_keys($data)[0])) {
            $data = array($data);
        }

        return $this->createSuccessJsonResponse(array(
            'selection_decks' => $data,
        ));
    }

    public function findDeckAction(Request $request)
    {
        return $this->get('api.shared.theory_repository')->findDeckById($request->request->get('deck_id'))
            ->success(function(PromiseInterface $promise) {
                return $this->createSuccessJsonResponse(array(
                    'deck' => $promise->getResult()->normalizeIfOneExists()->toArray(),
                ));
            })
            ->failure(function() use ($request) {
                return $this->createFailedJsonResponse(array(
                    sprintf('Deck with id %d could not be found', $request->request->get('deck_id')),
                ));
            })
            ->getResult();
    }

    public function findAllByLessonAction(Request $request)
    {
        $data = $request->request->all();

        $theoryRepo = $this->get('api.shared.theory_repository');

        $resultResolver = $theoryRepo->findAllByLesson($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createSuccessJsonResponse(array(
                'theories' => array(),
            ));
        }

        $data = $resultResolver->getData();

        if (is_string(array_keys($data)[0])) {
            $data = array($data);
        }

        return $this->createSuccessJsonResponse(array(
            'theories' => $data,
        ));
    }

    public function findDeckByInternalNameAction(Request $request)
    {
        $data = $request->request->all();
        $theoryRepo = $this->get('api.shared.theory_repository');

        $resultResolver = $theoryRepo->findDeckByInternalName($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse();
    }

    public function findDeckSoundsAction(Request $request)
    {
        $deckId = $request->request->get('deck_id');

        return $this->get('api.shared.theory_repository')->findSoundsByDeckId($deckId)
            ->success(function(PromiseInterface $promise) {
                 return $this->createSuccessJsonResponse(array(
                     'sounds' => $promise->getResult()->toArray(),
                 ));
            })
            ->failure(function() {
                return $this->createSuccessJsonResponse(array('sounds' => array()));
            })
            ->getResult();

    }
}
