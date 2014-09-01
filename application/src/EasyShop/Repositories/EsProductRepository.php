<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsProductItem;
use EasyShop\Entities\EsProductItemAttr;

class EsProductRepository extends EntityRepository
{
    
    /**
     * Returns the details of a product attribute
     *
     * @param integer $productId
     * @param string $fieldName
     * @param string $fieldValue
     * @return mixed
     *
     */
    public function getProductAttributeDetailByName($productId, $fieldName, $fieldValue)
    {
    
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('name', 'attr_name');
        $rsm->addScalarResult('attr_value', 'attr_value');
        $rsm->addScalarResult('attr_price', 'attr_price');
        $rsm->addScalarResult('attr_id', 'attr_id');
        $rsm->addScalarResult('image_path', 'image_path');
        $rsm->addScalarResult('is_other', 'is_other');
    
    
        $sql = " 
            SELECT h.field_name AS `name`, d.value_name AS attr_value, d.value_price AS attr_price,d.id_optional_attrdetail as attr_id, COALESCE(i.product_image_path,'') AS image_path, '1' as is_other 
            FROM es_optional_attrhead h 
            LEFT JOIN es_optional_attrdetail d ON h.id_optional_attrhead = d.head_id
            LEFT JOIN es_product_image i ON d.product_img_id = i.id_product_image
            WHERE h.product_id=:id AND h.`field_name`= :attr  AND d.`value_name` = :attr_value
            
            UNION
            
            SELECT  b.name, a.attr_value, a.attr_price,a.id_product_attr, '', '0'
            FROM es_product_attr a 
            LEFT JOIN  es_attr b ON a.attr_id = b.id_attr
            WHERE a.product_id =:id AND b.`name`= :attr  AND a.`attr_value` = :attr_value;
        ";
        
        $query = $this->em->createNativeQuery($sql,$rsm);
        $query->setParameter('id', $productId);
        $query->setParameter('attr', $fieldName);
        $query->setParameter('attr_value', $fieldValue);
        return $query->getOneOrNullResult();
    }

    /**
     * Returns the inventory detail of a product
     * 
     * @param integer $productId
     * @param bool $isVerbose 
     * @return mixed
     *
     */
    public function getProductInventoryDetail($productId, $isVerbose = false)
    {
        $this->em =  $this->_em;
        

        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('id_product_item', 'id_product_item');
        $rsm->addScalarResult('quantity', 'quantity');
        $rsm->addScalarResult('product_attr_id', 'product_attr_id');
        $rsm->addScalarResult('is_other', 'is_other');
        
        $sql = "    
            SELECT a.id_product_item, a.quantity, COALESCE(b.product_attr_id,0) as product_attr_id,
            COALESCE(b.is_other,0) as is_other  FROM es_product_item a
            LEFT JOIN es_product_item_attr b ON b.product_id_item = a.id_product_item 
            WHERE a.product_id = :product_id
        ";
        
        $defaultQuery = $this->em->createNativeQuery($sql, $rsm);
        $defaultQuery->setParameter('product_id', $productId);
        
        if(!$isVerbose){
            $result = $defaultQuery->getResult();
        }
        else{
            $rsm = new ResultSetMapping(); 
            $rsm->addScalarResult('id_product_item', 'id_product_item');
            $rsm->addScalarResult('quantity', 'quantity');
            $rsm->addScalarResult('product_attr_id', 'product_attr_id');
            $rsm->addScalarResult('is_other', 'is_other');
            $rsm->addScalarResult('attr_lookuplist_item_id', 'attr_lookuplist_item_id');
            $rsm->addScalarResult('attr_value', 'attr_value');
            
            $sql = " 
                SELECT a.id_product_item, a.quantity, COALESCE(b.product_attr_id,0) as product_attr_id , 
                COALESCE(b.is_other,0) as is_other, COALESCE(d.id_attr_lookuplist_item,0) as attr_lookuplist_item_id,
                d.name as attr_value
                FROM es_product_item a
                INNER JOIN es_product_item_attr b ON b.product_id_item = a.id_product_item AND b.is_other = 0
                LEFT JOIN es_product_attr c ON c.id_product_attr = b.product_attr_id
                LEFT JOIN es_attr e ON e.id_attr =  c.attr_id
                LEFT JOIN es_attr_lookuplist_item d ON LCASE(d.name) = LCASE(c.attr_value) AND e.attr_lookuplist_id = d.attr_lookuplist_id
                WHERE a.product_id = :product_id
                
                UNION

                SELECT a.id_product_item, a.quantity, COALESCE(b.product_attr_id,0) as product_attr_id , 
                COALESCE(b.is_other,0) as is_other, '0' as attr_lookuplist_item_id, d.value_name
                FROM es_product_item a
                INNER JOIN es_product_item_attr b ON b.product_id_item = a.id_product_item AND b.is_other = 1
                LEFT JOIN es_optional_attrdetail d ON d.id_optional_attrdetail = b.product_attr_id
                WHERE a.product_id = :product_id
            ";
            
            $query = $this->em->createNativeQuery($sql, $rsm);
            $query->setParameter('product_id', $productId);
            
            $result = $query->getResult();

            /**
             * In cases where the previous query does not return any result due to the INNER JOIN with 
             * es_product_item_attr (which happens with the default qty), we query using the non-verbose
             * version to get the default quantity result set.
             */
            if(count($data) === 0){
                $result = $defaultQuery->getResult();
            }
        }
        
  
        return $result;
    }
    
}
