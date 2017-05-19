<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Lesson;
use AdminBundle\Form\Type\Generic\TraitType\CheckboxTypeTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class LessonType extends AbstractType
{
    use TextTypeTrait, CheckboxTypeTrait;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addTextType('name', $builder)
            ->addCheckboxType('isInitialLesson', $builder);

        $builder
            ->add('lessonTexts', CollectionType::class, array(
                'label' => 'Add lesson text ...',
                'entry_type' => LessonTextType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ));
    }
    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'form';
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Lesson::class,
        ));
    }
}