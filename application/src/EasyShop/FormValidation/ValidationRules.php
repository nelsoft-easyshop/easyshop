<?php

namespace EasyShop\FormValidation;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * Validation Rules Class
 *
 * refer to: http://symfony.com/doc/current/reference/constraints.html
 * for the complete constraint list
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
                            new Assert\File(['maxSize' => '5M']
                                )
                            )
                ),
            'register' => array(
                    'username' => array(
                                new Assert\NotBlank(),
                                #new Assert\Min(5),
                               # new Assert\Max(25),
                    ),
                ),
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
