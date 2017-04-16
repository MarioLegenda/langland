<?php

namespace AdminBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class CategoryExistsConstraint extends Constraint
{
    public $message = 'Category \'%category%\' already exists';
}