<?php

namespace AdminBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class OneLanguageInfoConstraint extends Constraint
{
    public $message = 'This language already contains its own language info. One language can have only one language info';
}