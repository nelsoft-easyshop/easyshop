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

    /**
     * Gets the shipping details (payment_model->getShippingDetails)
     *
     * @param integer $productId
     * @param integer $productItemId
     * @param integer $cityId
     * @param integer $regionId
     * @param integer $majorIslandId
     * @return mixed
     */
    public function getShippingDetails($productId ,$productItemId, $cityId, $regionId, $majorIslandId)
    {
        $this->em = $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('location', 'location');
        $rsm->addScalarResult('type', 'type');
        $rsm->addScalarResult('parent_id', 'parent_id');
        $rsm->addScalarResult('price', 'price');
        $rsm->addScalarResult('product_id', 'product_id');
        $rsm->addScalarResult('product_item_id', 'product_item_id');
        $rsm->addScalarResult('is_cod', 'is_cod');
        $rsm->addScalarResult('availability', 'availability');

        $sql = "
            SELECT 
                es_location_lookup.id_location AS id
                , es_location_lookup.location
                , es_location_lookup.`type`
                , es_location_lookup.`parent_id`
                , COALESCE(price, '0') AS price
                , shipping.product_id 
                , COALESCE(product_item_id, 'Not Available') AS product_item_id
                , is_cod 
                , CASE es_location_lookup.`type` 
                WHEN '0' THEN   
                    IF(es_location_lookup.id_location = (SELECT COALESCE(1,0)), 'Available', 'Not Avialable')
                WHEN '1' THEN   
                    IF(es_location_lookup.id_location = (SELECT COALESCE(:majorIslandId,0)), 'Available', 'Not Avialable')
                WHEN '2' THEN 
                    IF(es_location_lookup.id_location = (SELECT COALESCE(:regionId,0)), 'Available', 'Not Avialable')
                WHEN '3' THEN 
                    IF(es_location_lookup.id_location = (SELECT COALESCE(:cityId,0)), 'Available', 'Not Avialable')
                END AS availability
            FROM
                `es_location_lookup` 
                LEFT OUTER JOIN 
                (
                    SELECT 
                        a.`product_item_id`
                        , b.product_id
                        , c.location AS shipping_location
                        , c.`id_location` AS shipping_id_location
                        , c.`type` AS shipping_type
                        , b.`price` 
                        , d.is_cod 
                    FROM
                        `es_product_shipping_detail` a
                        , `es_product_shipping_head` b
                        , `es_location_lookup` c 
                        , `es_product` d 
                    WHERE b.`id_shipping` = a.`shipping_id` 
                        AND b.`location_id` = c.`id_location` 
                        AND d.`id_product` = b.`product_id` 
                ) AS shipping 
                ON shipping.shipping_id_location = es_location_lookup.`id_location` 
            WHERE es_location_lookup.`type` IN (0, 1, 2, 3) 
                AND COALESCE(product_item_id, 'Not Available') != 'Not Available' 
                AND
                (CASE es_location_lookup.`type` 
                WHEN '0' THEN   
                    IF(es_location_lookup.id_location = (SELECT COALESCE(1,0)), 'Available', 'Not Avialable')
                WHEN '1' THEN   
                    IF(es_location_lookup.id_location = (SELECT COALESCE(:majorIslandId,0)), 'Available', 'Not Avialable')
                WHEN '2' THEN 
                    IF(es_location_lookup.id_location = (SELECT COALESCE(:regionId,0)), 'Available', 'Not Avialable')
                WHEN '3' THEN 
                    IF(es_location_lookup.id_location = (SELECT COALESCE(:cityId,0)), 'Available', 'Not Avialable')
                END ) = 'Available'
                AND `product_id` = :productId  
                AND `product_item_id` = :productItemId
                ORDER BY price ASC LIMIT 1
        ";

        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameter('productId', $productId);
        $query->setParameter('productItemId', $productItemId);
        $query->setParameter('cityId', $cityId);
        $query->setParameter('regionId', $regionId);
        $query->setParameter('majorIslandId', $majorIslandId);
        return $query->getResult();
    }
    
}
