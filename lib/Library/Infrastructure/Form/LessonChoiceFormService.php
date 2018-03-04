<?php

namespace Library\Infrastructure\Form;

use AdminBundle\Entity\Lesson;
use AdminBundle\Entity\Word;
use Doctrine\ORM\EntityManager;
use Library\Infrastructure\Form\Transformer\SingleChoiceTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormBuilderInterface;

class LessonChoiceFormService
{
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    private $request;
    /**
     * LanguageChoiceFormService constructor.
     * @param EntityManager $em
     * @param RequestStack $requestStack
     */
    public function __construct(EntityManager $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->request = $requestStack->getMasterRequest();
    }
    /**
     * @param string $entityName
     * @param FormBuilderInterface $builder
     */
    public function addLessonChoice(
        string $entityName,
        FormBuilderInterface $builder
    ) {
        $entity = null;

        if ($this->request->get('id')) {
            $entityId = $this->request->get('id');

            $entity = $this->em->getRepository(sprintf('AdminBundle:%s', $entityName))->find($entityId);
        }

        $builder
            ->add('lesson', ChoiceType::class, array(
                'label' => 'Choose lesson: ',
                'placeholder' => 'Choose lesson',
                'choices' => $this->createLessonChoices($this->em),
                'choice_label' => function ($choice, $key, $value) {
                    return ucfirst($key);
                }
            ));

        if ($entity instanceof Word) {
            $builder->get('lesson')->addModelTransformer(new SingleChoiceTransformer(
                ($entity->getLesson() instanceof Lesson) ? $entity->getLesson()->getId() : $entity->getLesson(),
                $this->em->getRepository('AdminBundle:Lesson')
            ));
        }
    }
    /**
     * @param EntityManager $em
     * @return array
     */
    private function createLessonChoices(EntityManager $em)
    {
        $lessons = $em->getRepository('AdminBundle:Lesson')->findAll();

        $choices = array();

        foreach ($lessons as $lesson) {
            $name = sprintf('%s | %s', $lesson->getName(), $lesson->getCourse()->getLanguage()->getName());
            $choices[$name] = $lesson->getId();
        }

        return $choices;
    }
}