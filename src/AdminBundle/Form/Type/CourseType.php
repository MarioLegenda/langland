<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Course;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AdminBundle\Transformer\SingleChoiceTransformer;
use AdminBundle\Entity\Language;

class CourseType extends AbstractType
{
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

        $builder
            ->add('language', ChoiceType::class, array(
                'label' => 'Language: ',
                'placeholder' => 'Choose language',
                'choices' => $this->createLanguageChoices(),
                'choice_label' => function($choice, $key, $value) {
                    return ucfirst($key);
                }
            ))
            ->add('name', TextType::class, array(
                'label' => 'Name: ',
                'attr' => array(
                    'placeholder' => 'click \'n type ...',
                    'autofocus' => true,
                )
            ))
            ->add('whatToLearn', TextareaType::class, array(
                'label' => 'What the user will learn?',
                'attr' => array(
                    'placeholder' => 'This will be shown in the course list of a language',
                    'rows' => 10,
                    'cols' => 40,
                ),
            ));

        $builder->get('language')->addModelTransformer(new SingleChoiceTransformer(
            ($course->getLanguage()) instanceof Language ? $course->getLanguage()->getId() : null,
            $this->em->getRepository('AdminBundle:Language')
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

    private function createLanguageChoices()
    {
        $languages = $this->em->getRepository('AdminBundle:Language')->findAll();

        $choices = array();

        foreach ($languages as $language) {
            $choices[$language->getName()] = $language->getId();
        }

        return $choices;
    }
}