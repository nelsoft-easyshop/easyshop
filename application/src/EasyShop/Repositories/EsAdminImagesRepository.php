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
    public function insertImage($filename)
    {

        $checkfilename = str_replace(' ', '_', $filename);

        $qb = $this->_em->createQueryBuilder()
                            ->select('pi')
                            ->from('EasyShop\Entities\EsAdminImages','pi')
                            ->where('pi.imageName = :name')
                            ->setParameter('name', $checkfilename)
                            ->getQuery();

        $result = $qb->getResult();

        $adminImage = new EsAdminImages();
        
        if(!$result) {
            $adminImage->setImageName($checkfilename);
            $this->_em->persist($adminImage);
            $this->_em->flush();        

        }
    }

    /**
     * Deletes an image
     * @param  int $imageId
     * @return bool
     */
    public function deleteImage($imageId)
    {
        try {
            $obj = $this->_em->getRepository('EasyShop\Entities\EsAdminImages')->find($imageId);
            $this->_em->remove($obj);
            $this->_em->flush();
            return true;
        }
        catch(Exception $e){
            return false;
        }
    }
}
