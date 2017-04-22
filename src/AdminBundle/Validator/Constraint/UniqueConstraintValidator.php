<?php

namespace AdminBundle\Validator\Constraint;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class UniqueConstraintValidator extends ConstraintValidator
{
    /**
     * @var EntityManager $repo
     */
    private $em;
    /**
     * CategoryExistsConstraintValidator constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $repo = $this->em->getRepository($constraint->repository);

        $result = $repo->findBy(array(
            $constraint->field => $value,
        ));

        if (!empty($result)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%field%', $value)
                ->addViolation();
        }
    }
}