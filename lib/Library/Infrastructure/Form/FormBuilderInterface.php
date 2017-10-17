<?php

namespace Library\Infrastructure\Form;

use Symfony\Component\Form\FormInterface;

interface FormBuilderInterface
{
    /**
     * @param mixed $data
     * @param array $options
     * @return FormInterface
     */
    public function getForm($data = null, array $options = array()) : FormInterface;
}