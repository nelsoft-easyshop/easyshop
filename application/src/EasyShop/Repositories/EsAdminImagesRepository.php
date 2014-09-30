<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsAdminImages; 

class EsAdminImagesRepository extends EntityRepository
{
    public function insertImages($filename)
    {

        $adminImage = new EsAdminImages;
        $adminImage->setImageName($filename);
        $this->_em->persist($adminImage);
        $this->_em->flush();        

    }
}
