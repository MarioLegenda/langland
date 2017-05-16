<?php

namespace AdminBundle\Form\Type\Generic\TraitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use AdminBundle\Form\Type\ImageType;

trait ImageTypeTrait
{
    /**
     * @param string $name
     * @param FormBuilderInterface $builder
     * @return $this
     */
    public function addImageType(string $name, FormBuilderInterface $builder)
    {
        $builder
            ->add($name, ImageType::class, array(
                'label' => false,
            ));

        return $this;
    }
    /**
     * @param string $name
     * @param FormBuilderInterface $builder
     * @return FormBuilderInterface
     */
    public function createImageType(string $name, FormBuilderInterface $builder) : FormBuilderInterface
    {
        return $builder
            ->create($name, ImageType::class, array(
                'label' => false,
            ));
    }
}