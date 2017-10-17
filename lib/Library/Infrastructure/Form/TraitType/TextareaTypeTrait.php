<?php

namespace Library\Infrastructure\Form\TraitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

trait TextareaTypeTrait
{
    /**
     * @param FormBuilderInterface $builder
     * @param string $name
     * @return $this
     */
    public function addTextareaType(string $label, string $name, FormBuilderInterface $builder)
    {
        $builder
            ->add($name, TextareaType::class, array(
                'label' => $label,
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