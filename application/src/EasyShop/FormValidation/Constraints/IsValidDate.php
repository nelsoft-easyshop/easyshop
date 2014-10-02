<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidDate extends Constraint
{
    public $message = 'Invalid date format for %string%.';
}

