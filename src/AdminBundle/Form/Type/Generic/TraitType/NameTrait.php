<?php

namespace AdminBundle\Form\Type\Generic\TraitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

trait NameTrait
{
    /**
     * @param FormBuilderInterface $builder
     * @return $this
     */
    public function buildName(FormBuilderInterface $builder)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Name: ',
                'attr' => array(
                    'placeholder' => 'Type name...'
                ),
            ));

        return $this;
    }
    /**
     * @param FormBuilderInterface $builder
     * @return FormBuilderInterface
     */
    public function createName(FormBuilderInterface $builder) : FormBuilderInterface
    {
        return $builder
            ->create('name', TextType::class, array(
                'label' => 'Name: ',
                'attr' => array(
                    'placeholder' => 'Type name...'
                ),
            ));
    }
}