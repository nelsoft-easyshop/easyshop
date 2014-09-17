<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsEmailUnique extends Constraint
{
    public $message = 'The email "%string%" is already in use';
        
}

