<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsMember as EsMember; 

/**
 *  es_member Repository
 */
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

    /**
     *  Fetch entries in es_member with exact storeName excluding excludeMemberId
     *
     *  @param integer $excludeMemberId
     *  @param string $storeName
     *
     *  @return boolean
     */
    public function getUsedStoreName($excludeMemberId, $storeName)
    {
        $em = $this->_em;

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('EasyShop\Entities\EsMember','m');
        $rsm->addFieldResult('m','id_member','idMember');
        $rsm->addFieldResult('m','store_name','storeName');

        $query = $em->createNativeQuery(
            'SELECT id_member, store_name
            FROM es_member
            WHERE id_member != ? AND store_name LIKE ?'
        , $rsm);

        $query->setParameter(1,$excludeMemberId);
        $query->setParameter(2,$storeName);

        return $query->getResult();
    }

    /**
     * Finds a member by username/email
     *
     * @param string $username Username/email of member
     *
     * @return EasyShop\Entities\EsMember
     */
    public function getUser($username)
    {
        // check if username is in DB
        $user = $this->_em->getRepository('EasyShop\Entities\EsMember')
                            ->findOneBy(['username' => $username]);

        if($user === NULL){
             $user = $this->_em->getRepository('EasyShop\Entities\EsMember')
                            ->findOneBy(['email' => $username]);
        }

        return $user;
    }
}
