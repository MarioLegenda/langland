<?php

namespace AdminBundle\Validator\Constraint;

use API\SharedDataBundle\Repository\Status;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use API\SharedDataBundle\Repository\CategoryRepository;

class CategoryExistsConstraintValidator extends ConstraintValidator
{
    /**
     * @var CategoryRepository $repo
     */
    private $repo;
    /**
     * CategoryExistsConstraintValidator constructor.
     * @param CategoryRepository $repo
     */
    public function __construct(CategoryRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $resultResolver = $this->repo->findByNameForWorkingLanguage(array(
            'category' => $value,
        ));

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%category%', $value)
                ->addViolation();
        }
    }
}