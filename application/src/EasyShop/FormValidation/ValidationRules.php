<?php

namespace EasyShop\FormValidation;

use Symfony\Component\Validator\Constraints as Assert;

/*
    Constraint List can be found here:
        http://symfony.com/doc/current/reference/constraints.html
*/

/**
 * Validation Rules Class
 *
 * @author LA Roberto
 */
class ValidationRules
{

    /**
     * Rules holder
     * 
     * @var mixed
     */
    private $rules = [];

    /**
     * Constructor.
     */
    public function __construct()
    {   
        $this->initValidationRules();
    }

    /**
     * Populates $rules
     */
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
                            new Assert\File([
                                'maxSize' => '5M',
                                'uploadIniSizeErrorMessage' => 'he file is too large. Allowed maximum size is 5 MB.'
                                ])
                            )
                ),
            'dummy' => array(
                    'field 1' => array(), // field 1
                    'field 2' => array(), // field 2
                    'field 3' => array() // field 3
                )
        );
    }

    /**
     * Returns rules for a specific form
     *
     * @param string $formName Name of the form
     *
     * @return mixed
     */
    public function getRules($formName)
    {
        return $this->rules[$formName];
    }
}
