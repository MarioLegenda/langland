<?php

namespace AdminBundle\Form\Type\Generic;

use AdminBundle\Entity\Word;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;
use AdminBundle\Entity\ContainsCategoriesInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AdminBundle\Transformer\MultipleChoiceTransformer;

class CategoryChoiceFormService
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
    public function addCategoryChoice(
        string $entityName,
        FormBuilderInterface $builder
    ) {
        $entity = null;

        if ($this->request->get('id')) {
            $entityId = $this->request->get('id');

            $entity = $this->em->getRepository(sprintf('AdminBundle:%s', $entityName))->find($entityId);

            if (!$entity instanceof ContainsCategoriesInterface) {
                throw new \RuntimeException(
                    sprintf('A form cannot use the %s if the working entity does not implement %s',
                        LanguageChoiceFormService::class,
                        ContainsCategoriesInterface::class)
                );
            }
        }

        $builder
            ->add('categories', ChoiceType::class, array(
                'label' => 'Choose categories: ',
                'placeholder' => 'Choose categories',
                'multiple' => true,
                'choices' => $this->createCategoryChoices($this->em),
                'choice_label' => function ($choice, $key, $value) {
                    return ucfirst($key);
                }
            ));

        $builder->get('categories')->addModelTransformer(new MultipleChoiceTransformer(
            ($entity instanceof Word and !$entity->getCategories()->isEmpty()) ? $entity->getCategories() : array(),
            $this->em->getRepository('AdminBundle:Category')
        ));
    }
    /**
     * @param EntityManager $em
     * @return array
     */
    private function createCategoryChoices(EntityManager $em)
    {
        $categories = $em->getRepository('AdminBundle:Category')->findAll();

        $choices = array();

        foreach ($categories as $category) {
            $choices[$category->getName()] = $category->getId();
        }

        return $choices;
    }
}