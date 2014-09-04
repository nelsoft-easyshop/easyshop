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
                        ->select('em.username')
                        ->from('EasyShop\Entities\EsMember','em')
                        ->getQuery();
                    
        $result = $qb->getResult();
        return $result;             
    }    

    /**
     * Checks if the userId exists
     *
     * @param int $id_member
     * @return int
     */
    public function getUserCountById($id_member)
    {
        $this->em = $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('id_member', 'id_member');

        $sql = " 
          SELECT *
          FROM es_member
          WHERE id_member = :id_member
        ";
        
        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameter('id_member', $id_member);
        
        $result = $query->getResult();
        return count($result);        
             
    }     

}