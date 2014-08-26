<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsProduct;

class EsProductRepository extends EntityRepository
{
    public function findByKeyword()
    {
        $qb = $em->createQueryBuilder();
 
        $qb
        ->select('Article', 'Comment')
        ->from('Entity\Article', 'Article')
        ->leftJoin('Article.comment', 'Comment');
        $query = $qb->getQuery();
         
        return $query->getResult();
    }
}
