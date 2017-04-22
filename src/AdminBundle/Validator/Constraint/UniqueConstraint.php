<?php

namespace AdminBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class UniqueConstraint extends Constraint
{
    public $message = '\'%field%\' already exists';
    public $repository = null;
    public $field = null;

    public function __construct($options = null)
    {
        parent::__construct($options);

        $this->repository = $options['repository'];
        $this->field = $options['field'];
    }
}