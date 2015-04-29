<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsAlphanumericSpaceUnderscore  extends Constraint
{
    public $message = 'The string "%string%" contains an illegal character: it can only contain letters, numbers, spaces and underscores';
}

