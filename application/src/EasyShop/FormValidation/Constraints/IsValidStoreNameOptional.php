<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidStoreNameOptional extends Constraint
{
    public $message = 'Store name must have a minimum of 5 and maximum of 60 characters';
}
