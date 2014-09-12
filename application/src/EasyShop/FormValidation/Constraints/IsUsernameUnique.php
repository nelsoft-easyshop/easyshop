<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsUsernameUnique extends Constraint
{
    public $message = 'The username "%string%" has already been taken';
        
}