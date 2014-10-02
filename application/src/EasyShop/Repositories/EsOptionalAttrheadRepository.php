<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 
use EasyShop\Entities\EsOptionalAttrdetail;
use EasyShop\Entities\EsOptionalAttrhead;

class EsOptionalAttrheadRepository extends EntityRepository
{
    /**
     * Method used for reverting rubbish data from admin product csv uploads
     * @param int $id
     */ 
    public function deleteAttrHeadById($id)
    {

        $query = $this->_em->createQuery("DELETE FROM EasyShop\Entities\EsOptionalAttrhead e 
            WHERE e.product = ?3");
        $query->setParameter(3, $id);
        $query->execute();      
    }

}
