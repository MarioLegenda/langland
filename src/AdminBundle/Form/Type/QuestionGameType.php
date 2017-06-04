<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\Game\QuestionGame;
use AdminBundle\Form\Type\Generic\TraitType\LessonChoiceTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextareaTypeTrait;
use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AdminBundle\Form\Type\QuestionGameAnswerType;

class QuestionGameType extends AbstractType
{
    use TextTypeTrait, TextareaTypeTrait, LessonChoiceTrait;
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * QuestionGameType constructor.
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
        $question = $options['question'];

        $this
            ->addLessonChoice($builder, $this->em, $question)
            ->addTextType('name', $builder)
            ->addTextareaType('description', $builder);

        $builder
            ->add('answers', CollectionType::class, array(
                'label' => 'Add answer ...',
                'entry_type' => QuestionGameAnswerType::class,
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
            'data_class' => QuestionGame::class,
        ));

        $resolver->setRequired('question');
    }
}