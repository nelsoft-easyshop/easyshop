<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsMobileUnique extends Constraint
{
    public $message = 'The mobile "%string%" is already in use';
        
}

