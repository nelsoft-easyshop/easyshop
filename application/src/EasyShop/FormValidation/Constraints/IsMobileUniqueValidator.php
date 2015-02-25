<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsMobileUniqueValidator extends ConstraintValidator
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

        $queryBuilder = $this->em->createQueryBuilder()
                                 ->select('b')
                                 ->from('\EasyShop\Entities\EsMember', 'b')
                                 ->where('b.contactno = :contact_number')
                                 ->setParameter('contact_number', $value);
        if($memberId !== null) {
            $queryBuilder->andWhere('b.idMember != :member')
                         ->setParameter('member', $memberId);
        }
        $userMobile =  $queryBuilder->getQuery()
                                    ->getOneOrNullResult();

        if($userMobile){
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}

