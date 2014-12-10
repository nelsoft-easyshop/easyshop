<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidTelephone extends Constraint
{
    public $message = 'The telephone that you have entered is invalid';
}

