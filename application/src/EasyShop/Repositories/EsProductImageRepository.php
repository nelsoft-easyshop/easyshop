<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

use EasyShop\Entities\EsProductImage;

class EsProductImageRepository extends EntityRepository
{
    
    /**
     * Returns the default image of a product
     *
     * @param integer $productId
     * @return EasyShop\Entities\EsProductImage
     *
     */
    public function getDefaultImage($productId)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder()
                            ->select('pi')
                            ->from('EasyShop\Entities\EsProductImage','pi')
                            ->where('pi.product = :productId')
                            ->andWhere('pi.isPrimary = 1')
                            ->setParameter('productId', $productId)
                            ->getQuery();

        $result = $qb->getOneOrNullResult();
        return $result;
    }

    /**
     * Returns the images of a product
     *
     * @param integer $productId
     * @return EasyShop\Entities\EsProductImage
     *
     */
    public function getProductImages($productId)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder()
                            ->select('pi')
                            ->from('EasyShop\Entities\EsProductImage','pi')
                            ->where('pi.product = :productId')
                            ->setParameter('productId', $productId)
                            ->getQuery();

        $result = $qb->getResult();
        return $result;
    }
    /**
     * Rename images uploaded from the admin side throuh csv
     *
     * @param string $filename
     * @param int $productid
     * @return EasyShop\Entities\EsProductImage
     *
     */
    public function renameImagesAndSlugsFromAdmin($newSlug, $filename, $productId, $productImageId)
    {

        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $q = $qb->update('EasyShop\Entities\EsProduct','pi')
                ->set('pi.slug', $qb->expr()->literal($newSlug))
                ->where('pi.idProduct = :productId')
                ->setParameter('productId', $productId)
                ->getQuery();
        $p = $q->execute();   


        $qb = $this->em->createQueryBuilder();
        $q = $qb->update('EasyShop\Entities\EsProductImage','pi')
                ->set('pi.productImagePath', $qb->expr()->literal($filename))
                ->where('pi.product = :productId')
                ->andWhere('pi.idProductImage = :idImage')
                ->setParameter('productId', $productId)
                ->setParameter('idImage', $productImageId)
                ->getQuery();
        $p = $q->execute();        

        return $p;
    }
    
}
