<?php

namespace AdminBundle\Validator\Constraint;

use API\SharedDataBundle\Repository\LanguageRepository;
use API\SharedDataBundle\Repository\Status;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LanguageExistsConstraintValidator extends ConstraintValidator
{
    /**
     * @var LanguageRepository $repo
     */
    private $repo;
    /**
     * LanguageExistsConstraintValidator constructor.
     * @param LanguageRepository $repo
     */
    public function __construct(LanguageRepository $repo)
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

        $data = array('language' => $value);

        $resultResolver = $this->repo->findLanguageByLanguage($data);

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%language%', $value)
                ->addViolation();
        }
    }
}