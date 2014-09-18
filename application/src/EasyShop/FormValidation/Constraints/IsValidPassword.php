<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidPassword extends Constraint
{
    public $message = 'The password must contain alphanumeric characters with no spaces';
        
}