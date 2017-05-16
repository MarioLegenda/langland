<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Course;
use AdminBundle\Form\Type\Generic\TraitType\CheckboxTypeTrait;
use AdminBundle\Form\Type\Generic\TraitType\LanguageChoiceTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextareaTypeTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    use TextTypeTrait, LanguageChoiceTrait, TextareaTypeTrait, CheckboxTypeTrait;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * CourseType constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $course = $options['course'];

        $this
            ->addTextType('name', $builder)
            ->addTextareaType('whatToLearn', $builder)
            ->addCheckboxType('initialCourse', $builder)
            ->addLanguageChoice($builder, $this->em, $course);
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
            'data_class' => Course::class,
        ));

        $resolver->setRequired('course');
    }
}