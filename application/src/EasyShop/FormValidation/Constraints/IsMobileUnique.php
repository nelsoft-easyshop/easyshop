<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsMobileUnique extends Constraint
{
    public $message = 'The mobile "0%string%" is already in use';
        
}

