<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\LessonWorkingData;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AdminBundle\Transformer\MultipleChoiceTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LessonWorkingDataType extends AbstractType
{
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * LessonWorkingDataType constructor.
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
        $lesson = $options['lesson'];

        $builder
            ->add('wordPools', ChoiceType::class, array(
                'label' => 'Choose word pool: ',
                'multiple' => true,
                'choices' => $this->createWordPoolChoices(),
                'choice_label' => function ($choice, $key, $value) {
                    return ucfirst($key);
                }
            ));

        $builder
            ->add('sentences', ChoiceType::class, array(
                'label' => 'Choose a sentence: ',
                'multiple' => true,
                'choices' => $this->createSentenceChoices(),
                'choice_label' => function ($choice, $key, $value) {
                    return ucfirst($key);
                }
            ));

        $wordPools = null;

        if ($lesson->getWorkingData() instanceof LessonWorkingData) {
            $wordPools = $lesson->getWorkingData()->getWordPools();
        }

        $builder->get('wordPools')->addModelTransformer(new MultipleChoiceTransformer(
            (!empty($wordPools)) ? $wordPools : array(),
            $this->em->getRepository('AdminBundle:SentenceWordPool')
        ));

        $sentences = null;

        if ($lesson->getWorkingData() instanceof LessonWorkingData) {
            $sentences = $lesson->getWorkingData()->getSentences();
        }

        $builder->get('sentences')->addModelTransformer(new MultipleChoiceTransformer(
            (!empty($sentences)) ? $sentences : array(),
            $this->em->getRepository('AdminBundle:Sentence')
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
            'data_data' => LessonWorkingData::class,
        ));

        $resolver->setRequired('lesson');
    }

    private function createWordPoolChoices()
    {
        $choices = array();

        $wordPools = $this->em->getRepository('AdminBundle:SentenceWordPool')->findAll();

        foreach ($wordPools as $wordPool) {
            $choices[$wordPool->getName()] = $wordPool->getId();
        }

        return $choices;
    }

    private function createSentenceChoices()
    {
        $choices = array();

        $sentences = $this->em->getRepository('AdminBundle:Sentence')->findAll();

        foreach ($sentences as $sentence) {
            $choices[$sentence->getName()] = $sentence->getId();
        }

        return $choices;
    }
}