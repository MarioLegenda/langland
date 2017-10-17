<?php

namespace Library\Infrastructure\Form;

use Symfony\Component\Form\FormInterface;

interface FormBuilderInterface
{
    /**
     * @param string $type
     * @param mixed $data
     * @param array $options
     * @return FormInterface
     */
    public function getForm(string $type, $data = null, array $options = array()) : FormInterface;
}