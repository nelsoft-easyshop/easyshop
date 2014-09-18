<?php

namespace EasyShop\FormValidation\FormHelpers;

use Symfony\Component\Form\Form;

class FormErrorHelper
{
    /**
     * Work-around for bug where Symfony (2.3) does not return errors from custom validaters,
     * when you call $form->getErrors().
     * Based on code submitted in a comment here by yapro:
     * https://github.com/symfony/symfony/issues/7205
     *
     * @param Form $form
     * @return array Associative array of all errors
     */
    public function getFormErrors($form)
    {
        $errors = array();

        if ($form instanceof Form) {
            foreach ($form->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }

            foreach ($form->all() as $key => $child) {
                /** @var $child Form */
                if ($err = $this->getFormErrors($child)) {
                    $errors[$key] = $err;
                }
            }
        }

        return $errors;
    }
}

