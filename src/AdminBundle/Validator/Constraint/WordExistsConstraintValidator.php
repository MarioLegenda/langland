<?php

namespace AdminBundle\Validator\Constraint;

use API\SharedDataBundle\Repository\WordRepository;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use API\SharedDataBundle\Repository\Status;

class WordExistsConstraintValidator extends ConstraintValidator
{
    /**
     * @var WordRepository $repo
     */
    private $repo;
    /**
     * WordExistsConstraintValidator constructor.
     * @param WordRepository $repo
     */
    public function __construct(WordRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return null
     */
    public function validate($value, Constraint $constraint)
    {
        if (!is_string($value)) {
            return null;
        }
    }
}