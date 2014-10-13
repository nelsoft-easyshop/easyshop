<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class IsValidMobileOptional extends Constraint
{
    public $message = 'The mobile number must begin with 09 or 08 and be 11 digits long';
}
