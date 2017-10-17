<?php

namespace Library\Infrastructure\Form\TraitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

trait TextTypeTrait
{
    /**
     * @param FormBuilderInterface $builder
     * @param string $name
     * @return $this
     */
    public function addTextType(string $label, string $name, FormBuilderInterface $builder)
    {
        $builder
            ->add($name, TextType::class, array(
                'label' => $label,
            ));

        return $this;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param string $name
     * @return FormBuilderInterface
     */
    public function createTextType(string $name, FormBuilderInterface $builder) : FormBuilderInterface
    {
        return $builder
            ->create($name, TextType::class, array(
                'label' => 'Name: ',
            ));
    }
}