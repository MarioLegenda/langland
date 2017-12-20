<?php

namespace LearningMetadata\Infrastructure\Form\Type;

use AdminBundle\Entity\Course;
use Library\Infrastructure\Form\LanguageChoiceFormService;
use Library\Infrastructure\Form\TraitType\CheckboxTypeTrait;
use Library\Infrastructure\Form\TraitType\TextareaTypeTrait;
use Library\Infrastructure\Form\TraitType\TextTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    use TextTypeTrait, TextareaTypeTrait, CheckboxTypeTrait;
    /**
     * @var LanguageChoiceFormService $em
     */
    private $languageChoiceService;
    /**
     * WordType constructor.
     * @param LanguageChoiceFormService $languageChoiceFormService
     */
    public function __construct(LanguageChoiceFormService $languageChoiceFormService)
    {
        $this->languageChoiceService = $languageChoiceFormService;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addTextType('Course name: ', 'name', $builder)
            ->addTextareaType('Description: ', 'whatToLearn', $builder)
            ->addCheckboxType('Is this the first course?', 'initialCourse', $builder);

        $this->languageChoiceService->getLanguageChoice('Course', $builder);
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
    }
}