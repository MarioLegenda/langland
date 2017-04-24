<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Course;
use AdminBundle\ValidationGroupResolver\GroupResolver;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
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
     * @var GroupResolver $groupResolver
     */
    private $groupResolver;
    /**
     * CourseType constructor.
     * @param EntityManager $em
     * @param GroupResolver $groupResolver
     */
    public function __construct(EntityManager $em, GroupResolver $groupResolver)
    {
        $this->em = $em;
        $this->groupResolver = $groupResolver;
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
            ))
            ->add('name', TextType::class, array(
                'label' => 'Name: ',
                'attr' => array(
                    'placeholder' => 'click \'n type ...',
                )
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
            'validation_groups' => $this->groupResolver->resolveValidationGroups(),
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