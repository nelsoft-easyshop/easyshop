<?php

namespace EasyShop\FormValidation;

use Symfony\Component\Validator\Constraints as Assert;

/*
    Constraint List can be found here:
        http://symfony.com/doc/current/reference/constraints.html
*/

class ValidationRules
{

    private $rules = [];

    public function __construct()
    {   
        $this->initValidationRules();
    }

    public function initValidationRules()
    {
        $this->rules = array(
            'bug_report' => array(
                    'title' => array(
                            new Assert\NotNull()
                        ),
                    'description' => array(
                            new Assert\NotNull()
                        ),
                    'file' => array(
                            new Assert\File(['maxSize' => '5M']
                                )
                            )
                ),
            'dummy' => array(
                    'field 1' => array(), // field 1
                    'field 2' => array(), // field 2
                    'field 3' => array() // field 3
                )
        );
    }

    public function getRules($formName)
    {
        return $this->rules[$formName];
    }
}