<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsMember as EsMember; 
use Doctrine\ORM\Query as Query;

/**
 *  es_member Repository
 */
class EsMemberRepository extends EntityRepository
{

    /**
     * Return member Entity based on Hydration option passed by asArray parameter
     * @param bool $asArray
     * @param string $username
     * @return Entity
     */    
    public function getHydratedMember($username, $asArray) 
    {
        $this->em =  $this->_em;        
        $query =  $this->em->createQueryBuilder()
                ->select('em')
                ->from('EasyShop\Entities\EsMember','em')
                ->where('em.username= :username')
                ->setParameter('username', $username)
                ->setMaxResults(1)
                ->getQuery();
        $hydrator = ($asArray) ? Query::HYDRATE_ARRAY : Query::HYDRATE_OBJECT;
        $member = $query->getResult($hydrator);
        $member = isset($member[0]) ? $member[0] : $member;    

        return $member;
    }
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
     *  Fetch member entity using $mobileNum
     */
    public function getUserExistingMobile($memberId, $mobileNum)
    {
        $em = $this->_em;
        $dql = "
            SELECT m
            FROM EasyShop\Entities\EsMember m
            WHERE m.idMember != :member_id
                AND m.contactno = :contact_no
        ";
        $query = $em->createQuery($dql)
                    ->setParameter('member_id', $memberId)
                    ->setParameter('contact_no', $mobileNum);

        return $query->getResult();
    }

    /**
     *  Fetch member entity using $email
     */
    public function getUserExistingEmail($memberId, $email)
    {
        $em = $this->_em;
        $dql = "
            SELECT m
            FROM EasyShop\Entities\EsMember m
            WHERE m.idMember != :member_id
                AND m.email = :email
        ";
        $query = $em->createQuery($dql)
                    ->setParameter('member_id', $memberId)
                    ->setParameter('email', $email);

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

    /**
     *  Fetch vendor details
     */
    public function getVendorDetails($vendorSlug)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();

        $rsm->addScalarResult('id_member', 'id_member');
        $rsm->addScalarResult('username', 'username');
        $rsm->addScalarResult('contactno', 'contactno');
        $rsm->addScalarResult('email', 'email');
        $rsm->addScalarResult('stateregion', 'stateregion');
        $rsm->addScalarResult('city', 'city');
        $rsm->addScalarResult('id_member', 'id_member');
        $rsm->addScalarResult('stateregionname', 'stateregionname');
        $rsm->addScalarResult('cityname', 'cityname');
        $rsm->addScalarResult('datecreated', 'datecreated');
        $rsm->addScalarResult('imgurl', 'imgurl');
        $rsm->addScalarResult('store_desc', 'store_desc');
        $rsm->addScalarResult('store_name', 'store_name');
        $rsm->addScalarResult('userslug', 'userslug');

        $sql = "
            SELECT m.id_member
                , m.username
                , IF(m.contactno != '', CONCAT('0',m.contactno), '') as contactno
                , m.email
                , a.stateregion
                , a.city
                , l1.location as stateregionname
                , l2.location as cityname
                , DATE_FORMAT(datecreated, '%M %Y') as datecreated
                , imgurl
                , store_desc
                , store_name
                , slug as userslug
            FROM es_member m
            LEFT JOIN es_address a on m.id_member = a.id_member AND a.type=0
            LEFT JOIN es_location_lookup l1 ON a.stateregion =  l1.id_location
            LEFT JOIN es_location_lookup l2 ON a.city = l2.id_location
            WHERE m.slug=:vendorslug
            LIMIT 1
        ";

        $query = $em->createNativeQuery($sql, $rsm)
            ->setParameter('vendorslug', $vendorSlug);

        $queryResult = $query->getResult();

        if( !empty($queryResult) ){
            $finalResult = reset($queryResult);
            if(strlen(trim($finalResult['store_name'])) === 0){
                $finalResult['store_name'] = $finalResult['username'];
            }
            return $finalResult;
        }
        else{
            return array();
        }
    }
}
