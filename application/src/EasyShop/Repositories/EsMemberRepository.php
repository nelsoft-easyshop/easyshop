<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsMember as EsMember; 


class EsMemberRepository extends EntityRepository
{

    /**
     * Returns the count of a all users
     *
     * @return int
     */
    public function getUserCount()
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder()
                        ->select('COUNT(em.username) as userCount')
                        ->from('EasyShop\Entities\EsMember','em')
                        ->getQuery();
                    
        $result = $qb->getOneOrNullResult();

        return $result['userCount'];             
    }    



}