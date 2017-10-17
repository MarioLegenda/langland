<?php

namespace Library\Infrastructure\Form\TraitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

trait CheckboxTypeTrait
{
    /**
     * @param string $name
     * @param FormBuilderInterface $builder
     * @return $this
     */
    public function addCheckboxType(string $label, string $name, FormBuilderInterface $builder)
    {
        $builder
            ->add($name, CheckboxType::class, array(
                'label' => $label,
            ));

        return $this;
    }
    /**
     * @param string $name
     * @param FormBuilderInterface $builder
     * @return FormBuilderInterface
     */
    public function createCheckboxType(string $name, FormBuilderInterface $builder) : FormBuilderInterface
    {
        return $builder->create($name, CheckboxType::class);
    }
}