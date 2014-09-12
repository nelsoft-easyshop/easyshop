<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsMemberCat;
use EasyShop\Entities\EsMemberProdcat;
use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsMember;

class EsMemberCatRepository extends EntityRepository
{
    /**
     *  Fetch custom categories of memberId in array form
     *
     *  @return array $customCategories
     */
    public function getCustomCategoriesArray($memberId)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_memcat','id_memcat');
        $rsm->addScalarResult('cat_name','cat_name');
        $rsm->addScalarResult('is_featured','is_featured');

        $sql = 'SELECT id_memcat
                    , cat_name
                    , is_featured
                FROM es_member_cat
                WHERE member_id = :member_id
                ';

        $query = $em->createNativeQuery($sql,$rsm)
                    ->setParameter('member_id', $memberId);

        return $query->getResult();
    }

}
