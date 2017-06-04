<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\LanguageInfo;
use AdminBundle\Event\PrePersistEvent;
use AdminBundle\Event\PreUpdateEvent;
use AdminBundle\Form\Type\LanguageInfoType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class LanguageInfoController extends RepositoryController
{
    public function indexAction()
    {
        $languageInfos = $this->getRepository('AdminBundle:LanguageInfo')->findAll();

        return $this->render('::Admin/LanguageInfo/index.html.twig', array(
            'languageInfos' => $languageInfos,
        ));
    }

    public function createAction(Request $request)
    {
        $languageInfo = new LanguageInfo();
        $form = $this->createForm(LanguageInfoType::class, $languageInfo, array(
            'languageInfo' => $languageInfo,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->dispatchEvent(PrePersistEvent::class, array(
                    'languageInfo' => $languageInfo,
                ));

                $em->persist($languageInfo);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Language created successfully')
                );

                return $this->redirectToRoute('admin_language_info_create');
            }
        }

        return $this->render('::Admin/LanguageInfo/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $languageInfoId)
    {
        $languageInfo = $this->getRepository('AdminBundle:LanguageInfo')->find($languageInfoId);

        if (empty($languageInfo)) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(LanguageInfoType::class, $languageInfo, array(
            'languageInfo' => $languageInfo,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->dispatchEvent(PrePersistEvent::class, array(
                    'languageInfo' => $languageInfo,
                ));

                $this->dispatchEvent(PreUpdateEvent::class, array(
                    'languageInfo' => $languageInfo,
                ));

                $em->persist($languageInfo);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Language edited successfully')
                );

                return $this->redirectToRoute('admin_language_info_edit', array(
                    'languageInfoId' => $languageInfo->getId(),
                ));
            }
        }

        return $this->render('::Admin/LanguageInfo/edit.html.twig', array(
            'languageInfo' => $languageInfo,
            'form' => $form->createView(),
        ));
    }

    private function removeDeletetedTexts(LanguageInfo $languageInfo)
    {
        $em = $this->get('doctrine')->getManager();

        $dbLanguageInfoTexts = $em->getRepository('AdminBundle:LanguageInfoText')->findBy(array(
            'languageInfo' => $languageInfo,
        ));

        foreach ($dbLanguageInfoTexts as $text) {
            if (!$languageInfo->hasLanguageInfoText($text)) {
                $em->remove($text);
            }
        }
    }
}