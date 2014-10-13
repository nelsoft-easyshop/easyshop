<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class IsValidAddressOptional extends Constraint
{
    public $message = 'must have a minimum of 5 and maximum of 250 characters';
}
