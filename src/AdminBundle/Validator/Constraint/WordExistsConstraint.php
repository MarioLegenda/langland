<?php

namespace AdminBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class WordExistsConstraint extends Constraint
{
    public $message = 'Language \'%language%\' already exists';
}