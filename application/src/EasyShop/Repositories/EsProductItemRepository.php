<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class EsProductItemRepository extends EntityRepository
{

    /**
     * Method used for reverting rubbish data from admin product csv uploads
     * @param int $id
     */
    public function deleteProductItemByProductID($id)
    {
        $query = $this->_em->createQuery("DELETE FROM EasyShop\Entities\EsProductItem e 
            WHERE e.product = ?7");
        $query->setParameter(7, $id);
        $query->execute(); 
    }

    /**
     * Get Product shipping attribute
     * @param  [type] $productId [description]
     * @return [type]            [description]
     */
    public function getProductShippingAttribute($productId)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();

        $rsm->addScalarResult('id_product_item', 'id_product_item'); 
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('attr_value', 'attr_value');
        $query = $em->createNativeQuery("
            SELECT pitm.id_product_item, attr.name, pattr.attr_value
            FROM es_product_item pitm
            LEFT JOIN es_product_item_attr piattr
                ON pitm.id_product_item = piattr.product_id_item AND piattr.is_other = 0
            LEFT JOIN es_product_attr pattr
                ON piattr.product_attr_id = pattr.id_product_attr
            LEFT JOIN es_attr attr
                ON pattr.attr_id = attr.id_attr 
            WHERE pitm.product_id = :productId
            
            UNION
            
            SELECT pitm.id_product_item, oattrh.field_name, oattr.value_name
            FROM es_product_item pitm
            LEFT JOIN es_product_item_attr piattr
                ON pitm.id_product_item = piattr.product_id_item AND piattr.is_other = 1
            LEFT JOIN es_optional_attrdetail oattr
                ON piattr.product_attr_id = oattr.id_optional_attrdetail
            LEFT JOIN es_optional_attrhead oattrh
                ON oattr.head_id = oattrh.id_optional_attrhead
            WHERE pitm.product_id = :productId
        ", $rsm);
        $query->setParameter('productId', $productId);
        $result = $query->execute();

        return $result;
    }
}
