<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsUserMobileUniqueValidator extends ConstraintValidator
{
    /**
     * The entity manager
     *
     */
    private $em;

    public function __construct()
    {
        $this->em = get_instance()->kernel->serviceContainer['entity_manager'];
    }

    public function validate($value, Constraint $constraint)
    {
        $memberId = $constraint->getMemberId();

        if($value[0] === '0' && strlen($value) === 11){
            $value = substr($value , 1);
        }

        $result = $this->em->createQueryBuilder()
                           ->select('b')
                           ->from('\EasyShop\Entities\EsMember', 'b')
                           ->where('b.idMember != :member')
                           ->andWhere('b.contactno = :contact_number')
                           ->setParameter('member', $memberId)
                           ->setParameter('contact_number', $value)
                           ->getQuery()
                           ->getOneOrNullResult();

        if($result){
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}

