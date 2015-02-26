<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsAccountNumberUniqueValidator extends ConstraintValidator
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
        $accountId =  $constraint->getAccountId();
        
        $queryBuilder = $this->em->createQueryBuilder()
                                ->select('b')
                                ->from('\EasyShop\Entities\EsBillingInfo', 'b')
                                ->where('b.member = :member')
                                ->andWhere('b.bankAccountNumber = :account_number')
                                ->andWhere('b.isDelete = :isDelete')
                                ->setParameter('member', $memberId)
                                ->setParameter('isDelete', false)
                                ->setParameter('account_number', $value);
        if($accountId !== null){
            $queryBuilder->andWhere('b.idBillingInfo != :idBillingInfo')
                         ->setParameter('idBillingInfo', $accountId);
        }
        $paymentAccount = $queryBuilder->getQuery()
                                        ->getOneOrNullResult();
                       
        if($paymentAccount){   
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}
