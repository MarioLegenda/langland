<?php

namespace AdminBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class LanguageExistsConstraint extends Constraint
{
    public $message = 'Language \'%language%\' already exists';
}