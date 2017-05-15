<?php

namespace AdminBundle\Form\Type\Generic\TraitType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AdminBundle\Form\Type\Generic\NameTextCollectionType;

trait NameTextTrait
{
    /**
     * @param FormBuilderInterface $builder
     * @return $this
     */
    public function buildNameText(FormBuilderInterface $builder)
    {
        $builder
            ->add('text', CollectionType::class, array(
                'label' => 'Add text ...',
                'entry_type' => NameTextCollectionType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ));

        return $this;
    }
    /**
     * @param string $name
     * @param FormBuilderInterface $builder
     * @return FormBuilderInterface
     */
    public function createNameText(string $name, FormBuilderInterface $builder) : FormBuilderInterface
    {
        return $builder
            ->create($name, CollectionType::class, array(
                'label' => 'Add text ...',
                'entry_type' => NameTextCollectionType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ));
    }
}