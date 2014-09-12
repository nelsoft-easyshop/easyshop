<?php

namespace EasyShop\FormValidation;

use Symfony\Component\Validator\Constraints as Assert;
use EasyShop\FormValidation\Constraints as CustomAssert;


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
     * The entity manager
     *
     */
    private $em;
     
     
    /**
     * Constructor.
     */
    public function __construct($em)
    {   
        $this->em = $em;
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
                                'uploadIniSizeErrorMessage' => 'The file is too large. Allowed maximum size is 5 MB.'
                                ])
                            )
                ),
            'register' => array(
                    'username' => array(
                                new Assert\NotBlank(),
                                new Assert\Length(['min' => '5', 
                                                   'max' => '25']),
                                new CustomAssert\ContainsAlphanumericUnderscore(),
                                new CustomAssert\IsUsernameUnique(),
                    ),
                    'password' => array(
                                new Assert\NotBlank(),
                                new Assert\Length(['min' => '6',]),
                                new CustomAssert\IsValidPassword(),
                    ),
                    'contactno' => array(
                                new Assert\Length(['min' => '11',
                                                     'max' => '11']),
                                new CustomAssert\IsMobileUnique(),
                                new CustomAssert\IsValidMobile(),
                    ),
                    'email' => array(
                                new Assert\NotBlank(),
                                new Assert\Email(),
                                new CustomAssert\IsEmailUnique(),
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
