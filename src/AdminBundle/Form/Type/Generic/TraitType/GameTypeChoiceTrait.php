<?php

namespace AdminBundle\Form\Type\Generic\TraitType;

use AdminBundle\Transformer\GameTypeTransformer;
use AdminBundle\Transformer\MultipleChoiceTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

trait GameTypeChoiceTrait
{
    /**
     * @param FormBuilderInterface $builder
     * @param EntityManager $em
     *
     * @return LessonChoiceTrait
     */
    public function addGameTypeChoice(
        FormBuilderInterface $builder,
        EntityManager $em
    )
    {
        $builder
            ->add('gameTypes', ChoiceType::class, array(
                'label' => 'Game types: ',
                'choices' => $this->createGameTypeChoices($em),
                'choice_label' => function($choice, $key, $value) {
                    return ucfirst($key);
                },
                'multiple' => true
            ));

        $builder->get('gameTypes')->addModelTransformer(new GameTypeTransformer($em));

        return $this;
    }

    private function createGameTypeChoices(EntityManager $em)
    {
        $gameTypes = $em->getRepository('AdminBundle:GameType')->findAll();

        $choices = array();

        foreach ($gameTypes as $gameType) {
            $choices[$gameType->getName()] = $gameType->getServiceName();
        }

        return $choices;
    }
}