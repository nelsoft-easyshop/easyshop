<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 
use Doctrine\ORM\Query\ResultSetMapping;

class EsProductShippingDetailRepository extends EntityRepository
{

    /**
     * Method used for reverting rubbish data from admin product csv uploads
     * @param int $id
     */
    public function deleteShippingDetailByProductItem($result)
    {
        $query = $this->_em->createQuery("DELETE FROM EasyShop\Entities\EsProductShippingDetail e 
        WHERE e.productItem = ?5");
        $query->setParameter(5, $result->getIdProductItem());
        $query->execute();
    }
    
    /**
     * Gets the shipping details by product Id
     *
     * @param integer $productId
     * @return mixed
     */
    public function getShippingDetailsByProductId($productId)
    {
        $this->em = $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('id_shipping', 'id_shipping');
        $rsm->addScalarResult('id_location', 'id_location');
        $rsm->addScalarResult('location', 'location');
        $rsm->addScalarResult('type', 'type');
        $rsm->addScalarResult('price', 'price');
        $rsm->addScalarResult('parent_id', 'parent_id');
        $rsm->addScalarResult('product_item_id', 'product_item_id');
        $rsm->addScalarResult('product_attr_id', 'product_attr_id');
        $rsm->addScalarResult('is_other', 'is_other');

        $sql = "SELECT 
                    a.id_shipping,
                    c.id_location,
                    c.location,
                    c.type,
                    a.price,
                    c.parent_id,
                    b.product_item_id,
                    COALESCE(d.product_attr_id, 0) as product_attr_id,
                    COALESCE(d.is_other, 0) as is_other
                FROM
                    es_product_shipping_head a
                        LEFT JOIN
                    es_product_shipping_detail b ON a.id_shipping = b.shipping_id
                        LEFT JOIN
                    es_location_lookup c ON a.location_id = c.id_location
                        LEFT JOIN
                    es_product_item_attr d ON b.product_item_id = d.product_id_item
                WHERE
                    a.product_id = :productId
                ORDER BY c.type";
        
        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameter('productId', $productId);
        $shippingDetails = $query->getResult();
        $formattedShippingDetails = array();
        foreach($shippingDetails as $shippingDetail){
            if(!array_key_exists($shippingDetail['id_shipping'], $formattedShippingDetails)){
                $formattedShippingDetails[$shippingDetail['id_shipping']] = array();
                $formattedShippingDetails[$shippingDetail['id_shipping']]['location'] = $shippingDetail['location'];
                $formattedShippingDetails[$shippingDetail['id_shipping']]['price'] = $shippingDetail['price'];
                $formattedShippingDetails[$shippingDetail['id_shipping']]['location_type'] = $shippingDetail['type'];
                $formattedShippingDetails[$shippingDetail['id_shipping']]['location_id'] = $shippingDetail['id_location'];
                $formattedShippingDetails[$shippingDetail['id_shipping']]['product_item_id'] = $shippingDetail['product_item_id'];
                $formattedShippingDetails[$shippingDetail['id_shipping']]['product_attribute_ids'] = array();
            }
            array_push($formattedShippingDetails[$shippingDetail['id_shipping']]['product_attribute_ids'], array('id' => $shippingDetail['product_attr_id'], 'is_other' => $shippingDetail['is_other']));
        }
        return $formattedShippingDetails;
       
    } 
    
}
