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
     *  @param string $storeName
     *  @param integer $excludeMemberId
     *  @return boolean
     */
    public function getUserWithStoreName($storeName, $excludeMemberId = null)
    {
        $em = $this->_em;

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('EasyShop\Entities\EsMember','m');
        $rsm->addFieldResult('m','id_member','idMember');
        $rsm->addFieldResult('m','username','username');
        $rsm->addFieldResult('m','store_name','storeName');

        $sql =  '
            SELECT 
                id_member, 
                store_name, 
                username
            FROM 
                es_member
            WHERE 
                BINARY store_name  = :storeName OR
                ((BINARY store_name IS NULL OR BINARY store_name = "") AND BINARY username = :storeName)
        ';
        
        if($excludeMemberId !== null){
            $sql .= ' AND id_member != :memberId';
        }
 
        $query = $em->createNativeQuery($sql, $rsm);

        $query->setParameter('storeName',$storeName);

        if($excludeMemberId !== null){
            $query->setParameter('memberId',$excludeMemberId);
        }

        return $query->getResult();
    }

    
    /**
     *  Fetch entries in es_member with storename or username
     *
     *  @param string $name
     *  @param integer $excludeMemberId
     *  @return boolean
     */
    public function getUserWithStoreNameOrUsername($name, $excludeMemberId = null)
    {
        $em = $this->_em;

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('EasyShop\Entities\EsMember','m');
        $rsm->addFieldResult('m','id_member','idMember');
        $rsm->addFieldResult('m','username','username');
        $rsm->addFieldResult('m','store_name','storeName');

        $sql =  '
            SELECT 
                id_member, 
                store_name, 
                username
            FROM 
                es_member
            WHERE 
                (store_name = :storeName OR username = :userName)
        ';
        
        if($excludeMemberId !== null){
            $sql .= ' AND id_member != :memberId';
        }
 
        $query = $em->createNativeQuery($sql, $rsm);

        $query->setParameter('storeName', $name);
        $query->setParameter('userName', $name);

        if($excludeMemberId !== null){
            $query->setParameter('memberId',$excludeMemberId);
        }

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
        $rsm->addScalarResult('address', 'address');
        $rsm->addScalarResult('stateregion', 'stateregion');
        $rsm->addScalarResult('city', 'city');
        $rsm->addScalarResult('country', 'country');
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
                , a.address
                , a.stateregion
                , a.city 
                , l1.location as stateregionname
                , l2.location as cityname
                , l3.location as country
                , DATE_FORMAT(datecreated, '%M %Y') as datecreated
                , imgurl
                , store_desc
                , store_name
                , slug as userslug
            FROM es_member m
            LEFT JOIN es_address a on m.id_member = a.id_member AND a.type=0
            LEFT JOIN es_location_lookup l1 ON a.stateregion =  l1.id_location
            LEFT JOIN es_location_lookup l2 ON a.city = l2.id_location
            LEFT JOIN es_location_lookup l3 ON a.country = l3.id_location
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

    /**
     * @param $member
     * @param $username
     * @return EsMember
     */
    public function updateUsername($member, $username)
    {
        $em = $this->_em;
        $member->setUsername($username);
        $em->flush();

        return $member;
    }

    /**
     * Merge social media account to existing EasyShop Account
     * @param $member
     * @param $oauthProvider
     * @return EsMember
     */
    public function updateOauthProvider($member, $oauthProvider)
    {
        $em = $this->_em;
        $member->setOauthProvider($oauthProvider);
        $em->flush();

        return $member;
    }
    
    /**
     * Get users with the given slug excluding provided memberId
     *
     * @param string $slug
     * @param integer $notMemberId
     * @return EasyShop\Entities\EsMember[]
     */
    public function getUsersWithSlug($slug, $notMemberId = null)
    {
        $queryBuilder =   $this->_em->createQueryBuilder();
        
        $queryBuilder->select('m')
                    ->from('EasyShop\Entities\EsMember','m')
                    ->where($queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('m.slug', ':slugA'),
                        $queryBuilder->expr()->eq('m.username', ':slugA')
                    ))
                    ->setParameter('slugA', $slug);

        if($notMemberId !== null){
            $queryBuilder->andWhere('m.idMember != :member_id')
                         ->setParameter('member_id', $notMemberId);
        }  
        return $queryBuilder->getQuery()
                            ->getResult();
    
    }
    
    /**
     * Deactivate / Activate account
     * @param $member
     * @param $activate
     * @return EsMember
     */
    public function accountActivation($member, $activate = true)
    {
        $em = $this->_em;
        $member->setIsActive($activate);
        $em->flush();

        return $member;
    }    

    /**
     * Update User Avatar
     * @param int $member
     * @param string $imagePath
     * @param bool $isForAvatar
     * @return Easyshop\Entities\EsMember
     */
    public function updateMemberImageUrl($member, $imagePath, $isForAvatar = true)
    {
        $em = $this->_em;        
        $member->setImgurl(rtrim($imagePath,"/"));
        if($isForAvatar) {
            $member->setIsHideAvatar(EsMember::DEFAULT_AVATAR_VISIBILITY);
        }
        else {
            $member->setIsHideBanner(EsMember::DEFAULT_AVATAR_VISIBILITY);
        }
        $member->setLastmodifieddate(new \DateTime('now'));
        $em->flush();
    }

    /**
     * Find member by username with case sensitive rule
     * @param  string $username [description]
     * @return EasyShop\Entities\EsMember
     */
    public function findOneByUsernameCase($username)
    {
        $em = $this->_em;
        $dql = "
            SELECT m
            FROM EasyShop\Entities\EsMember m
            WHERE m.username = BINARY(:username)
        ";
        $query = $em->createQuery($dql)
                    ->setParameter('username', $username)
                    ->setMaxResults(1);

        $member = $query->getResult();
        $member = isset($member[0]) ? $member[0] : $member;

        return $member;
    }

}
