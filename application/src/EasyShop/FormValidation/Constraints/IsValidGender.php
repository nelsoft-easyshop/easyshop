<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidGender extends Constraint
{
    public $message = 'The gender that you have entered is invalid';
}

