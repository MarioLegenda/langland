<?php

namespace Library\Infrastructure\Form\TraitType;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AdminBundle\Transformer\MultipleChoiceTransformer;

trait CategoryChoiceTrait
{
    /**
     * @param FormBuilderInterface $builder
     * @param EntityManager $em
     * @param $entity
     * @return $this
     */
    public function addCategoryChoice(FormBuilderInterface $builder, EntityManager $em, $entity)
    {
        $builder
            ->add('categories', ChoiceType::class, array(
                'label' => 'Choose categories: ',
                'placeholder' => 'Choose categories',
                'multiple' => true,
                'choices' => $this->createCategoryChoices($em),
                'choice_label' => function ($choice, $key, $value) {
                    return ucfirst($key);
                }
            ));


        $builder->get('categories')->addModelTransformer(new MultipleChoiceTransformer(
            (!$entity->getCategories()->isEmpty()) ? $entity->getCategories() : array(),
            $em->getRepository('AdminBundle:Category')
        ));

        return $this;
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