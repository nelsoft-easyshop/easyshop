<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsAlphanumericSpace  extends Constraint
{
    public $message = 'The string "%string%" contains an illegal character: it can only contain letters, numbers and spaces.';
}

