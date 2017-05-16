<?php

namespace AdminBundle\Form\Type\Generic\TraitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

trait TextareaTypeTrait
{
    /**
     * @param FormBuilderInterface $builder
     * @param string $name
     * @return $this
     */
    public function addTextareaType(string $name, FormBuilderInterface $builder)
    {
        $builder
            ->add($name, TextareaType::class, array(
                'label' => 'Text: ',
            ));

        return $this;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param string $name
     * @return FormBuilderInterface
     */
    public function createTextareaType(string $name, FormBuilderInterface $builder) : FormBuilderInterface
    {
        return $builder
            ->create($name, TextareaType::class, array(
                'label' => 'Text: ',
            ));
    }
}