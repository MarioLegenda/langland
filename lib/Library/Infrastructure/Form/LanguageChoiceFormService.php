<?php

namespace Library\Infrastructure\Form;

use AdminBundle\Entity\ContainsLanguageInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Library\Infrastructure\Form\Transformer\SingleChoiceTransformer;

class LanguageChoiceFormService
{
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    private $request;
    /**
     * LanguageChoiceFormService constructor.
     * @param EntityManager $em
     * @param RequestStack $requestStack
     */
    public function __construct(EntityManager $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->request = $requestStack->getMasterRequest();
    }
    /**
     * @param string $entityName
     * @param FormBuilderInterface $builder
     */
    public function getLanguageChoice(
        string $entityName,
        FormBuilderInterface $builder
    )
    {
        $languageId = null;

        if ($this->request->get('id')) {
            $entityId = $this->request->get('id');

            $entity = $this->em->getRepository(sprintf('AdminBundle:%s', $entityName))->find($entityId);

            if (!$entity instanceof ContainsLanguageInterface) {
                throw new \RuntimeException(
                    sprintf('A form cannot use the %s if the working entity does not implement %s',
                    LanguageChoiceFormService::class,
                    ContainsLanguageInterface::class)
                );
            }

            $languageId = $entity->getLanguage()->getId();
        }

        $builder
            ->add('language', ChoiceType::class, array(
                'label' => 'Language: ',
                'placeholder' => 'Choose language',
                'choices' => $this->createLanguageChoices($this->em),
                'choice_label' => function($choice, $key, $value) {
                    return ucfirst($key);
                }
            ));

        $builder->get('language')->addModelTransformer(new SingleChoiceTransformer(
            $languageId,
            $this->em->getRepository('AdminBundle:Language')
        ));
    }

    private function createLanguageChoices(EntityManager $em)
    {
        $languages = $em->getRepository('AdminBundle:Language')->findAll();

        $choices = array();

        foreach ($languages as $language) {
            $choices[$language->getName()] = $language->getId();
        }

        return $choices;
    }
}