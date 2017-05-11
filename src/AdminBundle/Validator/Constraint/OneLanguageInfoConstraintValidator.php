<?php

namespace AdminBundle\Validator\Constraint;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OneLanguageInfoConstraintValidator extends ConstraintValidator
{
    /**
     * @var EntityManager $em
     */
    private $em;
    /**
     * OneLanguageInfoConstraintValidator constructor.
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
        die("kreten");
    }
}