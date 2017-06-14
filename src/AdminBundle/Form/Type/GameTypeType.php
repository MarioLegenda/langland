<?php

namespace AdminBundle\Form\Type;

use AdminBundle\Entity\GameType;
use AdminBundle\Form\Type\Generic\TraitType\TextTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameTypeType extends AbstractType
{
    use TextTypeTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addTextType('Game type: ', 'name', $builder)
            ->addTextType('Game service name: ', 'serviceName', $builder);
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
            'data_class' => GameType::class,
        ));
    }
}