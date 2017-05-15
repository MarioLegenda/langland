<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Course;
use AdminBundle\Form\Type\Generic\TraitType\LanguageChoiceTrait;
use AdminBundle\Form\Type\Generic\TraitType\NameTrait;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    use NameTrait, LanguageChoiceTrait;
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
            ->buildName($builder)
            ->buildLanguageChoice($builder, $this->em, $course);

        $builder
            ->add('whatToLearn', TextareaType::class, array(
                'label' => 'What the user will learn?',
                'attr' => array(
                    'placeholder' => 'This will be shown in the course list of a language',
                    'rows' => 10,
                    'cols' => 40,
                ),
            ))
            ->add('initialCourse', CheckboxType::class, array(
                'label' => 'Mark as initial first course',
                'attr' => array(
                    'placeholder' => 'This course will be the first in course items list',
                ),
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
            'data_class' => Course::class,
        ));

        $resolver->setRequired('course');
    }
}