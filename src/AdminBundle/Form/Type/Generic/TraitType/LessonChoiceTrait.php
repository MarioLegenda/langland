<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 04.06.17.
 * Time: 14:47
 */

namespace AdminBundle\Form\Type\Generic\TraitType;

use AdminBundle\Entity\Lesson;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AdminBundle\Transformer\SingleChoiceTransformer;

trait LessonChoiceTrait
{
    /**
     * @param FormBuilderInterface $builder
     * @param EntityManager $em
     * @param $entity
     *
     * @return LessonChoiceTrait
     */
    public function addLessonChoice(
        FormBuilderInterface $builder,
        EntityManager $em,
        $entity)
    {
        $builder
            ->add('lesson', ChoiceType::class, array(
                'label' => 'Lesson: ',
                'placeholder' => 'Choose lesson',
                'choices' => $this->createLessonChoices($em),
                'choice_label' => function($choice, $key, $value) {
                    return ucfirst($key);
                }
            ));

        $builder->get('lesson')->addModelTransformer(new SingleChoiceTransformer(
            ($entity->getLesson()) instanceof Lesson ? $entity->getLesson()->getId() : null,
            $em->getRepository('AdminBundle:Lesson')
        ));

        return $this;
    }

    private function createLessonChoices(EntityManager $em)
    {
        $lessons = $em->getRepository('AdminBundle:Lesson')->findAll();

        $choices = array();

        foreach ($lessons as $lesson) {
            $choices[$lesson->getName()] = $lesson->getId();
        }

        return $choices;
    }
}