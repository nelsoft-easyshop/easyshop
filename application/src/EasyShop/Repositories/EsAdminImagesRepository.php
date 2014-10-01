<?php

namespace EasyShop\Repositories;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsAdminImages; 

class EsAdminImagesRepository extends EntityRepository
{

    /**
     * Inserts image name under es_admin_images 
     * @param string $filename
     */    
    public function insertImages($filename)
    {

        $checkfilename = str_replace(' ', '_', $filename);

        $qb = $this->_em->createQueryBuilder()
                            ->select('pi')
                            ->from('EasyShop\Entities\EsAdminImages','pi')
                            ->where('pi.imageName = :name')
                            ->setParameter('name', $checkfilename)
                            ->getQuery();

        $result = $qb->getResult();

        $adminImage = new EsAdminImages;
        if(!$result) {
            $adminImage->setImageName($checkfilename);
            $this->_em->persist($adminImage);
            $this->_em->flush();        

        }


    }
}
