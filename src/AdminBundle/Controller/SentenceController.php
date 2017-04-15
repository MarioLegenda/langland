<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Sentence;
use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Controller\MasterSecurityController;
use BlueDot\Entity\PromiseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SentenceController extends MasterSecurityController implements AdminAuthInterface
{
    public function createAction(Request $request)
    {
        $sentence = Sentence::createFromRequest($request);
        $response = $this->get('app.manual_validator')->validate($sentence);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        return $this->get('app.sentence_repository')
            ->create($sentence)
            ->success(function() {
                return $this->createSuccessJsonResponse();
            })
            ->failure(function() use ($sentence) {
                return $this->createFailedJsonResponse(array(
                    sprintf('Sentence with internal name \'%s\' already exists', $sentence->getInternalName()),
                ));
            })
            ->getResult();
    }

    public function findInternalNamesAction(Request $request)
    {
        return $this->get('app.sentence_repository')
            ->findInternalNames($request->request->get('lesson_id'))
            ->success(function(PromiseInterface $promise) {
                return $this->createSuccessJsonResponse(array(
                    'internal_names' => $promise->getResult(),
                ));
            })
            ->failure(function() {
                return $this->createSuccessJsonResponse(array(
                    'internal_names' => array(),
                ));
            })
            ->getResult();
    }

    public function findLessonSentenceAction(Request $request)
    {
        return $this->get('app.sentence_repository')
            ->findLessonSentence($request->request->all())
            ->success(function(PromiseInterface $promise) {
                return $this->createSuccessJsonResponse(array(
                    'sentence' => $promise->getResult(),
                ));
            })
            ->failure(function() {
                return $this->createFailedJsonResponse();
            })
            ->getResult();
    }

    public function updateLessonSentenceAction(Request $request)
    {
        return $this->get('app.sentence_repository')->updateLessonSentence(array(
            'data' => $request->request->all(),
        ))
            ->success(function(PromiseInterface $promise) {
                return $this->createSuccessJsonResponse(array(
                    'sentence' => $promise->getResult(),
                ));
            })
            ->failure(function() {
                return $this->createFailedJsonResponse(array(
                    'Internal name already exists'
                ));
            })
            ->getResult();
    }
}