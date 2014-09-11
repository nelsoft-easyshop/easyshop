<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsProduct; 
use EasyShop\Entities\EsProductImage;
use EasyShop\Entities\EsBrand;
use EasyShop\Entities\EsProductItem;
use EasyShop\Entities\EsProductItemAttr;

class EsProductRepository extends EntityRepository
{
    /**
     * Find all records in database related to string given in parameters
     * @param  array  $stringCollection 
     * @return object
     */
    public function findByKeyword($stringCollection = array())
    {
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('EasyShop\Entities\EsProduct', 'u');
        $rsm->addFieldResult('u', 'idProduct', 'idProduct');
        $rsm->addFieldResult('u', 'name', 'name');
        $rsm->addFieldResult('u', 'price', 'price'); 
        $rsm->addFieldResult('u', 'brief', 'brief');
        $rsm->addFieldResult('u', 'slug', 'slug');
        $rsm->addFieldResult('u', 'condition', 'condition');
        $rsm->addFieldResult('u', 'startdate', 'startdate');
        $rsm->addFieldResult('u', 'enddate', 'enddate');
        $rsm->addFieldResult('u', 'isPromote', 'isPromote');
        $rsm->addFieldResult('u', 'promoType', 'promoType');
        $rsm->addFieldResult('u', 'discount', 'discount');
        $rsm->addFieldResult('u', 'isSoldOut', 'isSoldOut');
        $query = $this->em->createNativeQuery("
            SELECT `id_product` as idProduct
                , `name`
                , `price`
                , `slug`
                , `brief`
                , `condition`
                , `startdate`
                , `enddate`
                , `is_promote` as isPromote
                , `promo_type` as promoType
                , `discount`
                , `is_sold_out` as isSoldOut
                , `weight`
            FROM (
                    SELECT 
                        (MATCH (`name`) AGAINST (:param0 IN BOOLEAN MODE) * 3) +
                        (MATCH (`search_keyword`) AGAINST (:param0 IN BOOLEAN MODE) * 1.5) +
                        MATCH (`name`) AGAINST (:param1 IN BOOLEAN MODE) +
                        (MATCH (`search_keyword`) AGAINST (:param1 IN BOOLEAN MODE) * 0.5) +
                        (MATCH (`name`) AGAINST (:param2 IN BOOLEAN MODE) * 5) +
                        (MATCH (`search_keyword`) AGAINST (:param2 IN BOOLEAN MODE) * 2) +
                        ((REPLACE (`search_keyword`, ' ', '') LIKE :param3 )  * 0.005)
                         AS weight,
                        id_product,`name`,price,brief,slug,`condition`,startdate, enddate,is_promote,promo_type,discount
                        ,`is_sold_out`
                    FROM es_product
                    WHERE is_delete = 0 AND is_draft = 0 
                    AND (
                        MATCH (`search_keyword`) AGAINST (:param1 IN BOOLEAN MODE)
                        OR 
                        REPLACE (`search_keyword`, ' ', '') LIKE :param3
                    )
                ) as score_table
            HAVING weight > 0
            ORDER BY weight DESC,name ASC
        ", $rsm);
        $query->setParameter('param0', $stringCollection[0]);
        $query->setParameter('param1', $stringCollection[1]); 
        $query->setParameter('param2', $stringCollection[2]); 
        $query->setParameter('param3', "%".$stringCollection[3]."%"); 
        $results = $query->execute();  

        return $results;
    }

    /**
     * Get all product details with given product id
     * @param  array   $productId
     * @param  integer $offset
     * @param  integer $perPage
     * @return mixed
     */
    public function getProductDetailsByIds($productId = array(),$offset = 0,$perPage = 1)
    {   
        if(count($productId) > 0){
            $this->em =  $this->_em;
            $rsm = new ResultSetMapping(); 
                $rsm->addScalarResult('idProduct', 'idProduct');
                $rsm->addScalarResult('name', 'name');
                $rsm->addScalarResult('brief', 'brief');
                $rsm->addScalarResult('price', 'price');
                $rsm->addScalarResult('slug', 'slug');
                $rsm->addScalarResult('condition', 'condition');
                $rsm->addScalarResult('startdate', 'startdate');
                $rsm->addScalarResult('enddate', 'enddate');
                $rsm->addScalarResult('isPromote', 'isPromote');
                $rsm->addScalarResult('promoType', 'promoType');
                $rsm->addScalarResult('discount', 'discount');
                $rsm->addScalarResult('isSoldOut', 'isSoldOut');
                $rsm->addScalarResult('productImagePath', 'productImagePath');
                $rsm->addScalarResult('username', 'username');

                $query = $this->em->createNativeQuery("
                    SELECT 
                        p.id_product as idProduct
                        , p.name as name
                        , p.brief as brief
                        , p.price as price
                        , p.slug as slug
                        , p.condition
                        , p.startdate as startdate
                        , p.enddate as enddate
                        , p.is_promote as isPromote
                        , p.promo_type as promoType
                        , p.discount as discount
                        , p.is_sold_out as isSoldOut
                        , i.product_image_path as productImagePath
                        , m.username as username
                    FROM 
                        es_product p
                        LEFT JOIN es_product_image i ON p.id_product = i.product_id AND i.is_primary = 1
                        LEFT JOIN es_member m ON m.id_member = p.member_id
                    WHERE p.id_product IN (:ids)
                    ORDER BY FIELD(p.id_product,:ids)
                    LIMIT :offset, :page ", $rsm);
                $query->setParameter('ids', $productId);
                $query->setParameter('offset', $offset * $perPage);
                $query->setParameter('page', $perPage);
                $results = $query->execute();

                return $results;
            }
            
        return false;
    }

    /**
     * Get All attributes of given productid
     * @param  array  $productId
     * @return array
     */
    public function getAttributes($productId = array(),$filter = false,$additionalString = "",$parameters = array())
    {
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('product_id', 'product_id');

        if($filter){
            $selectString = 'COUNT(*) as cnt,product_id';
        }
        else{
            $selectString = 'name,attr_value';
            $rsm->addScalarResult('name', 'head');
            $rsm->addScalarResult('attr_value', 'value');
        }

        $query = $this->em->createNativeQuery("
            SELECT ".$selectString." FROM (
                SELECT 
                    a.product_id as product_id
                    , b.name AS name
                    , a.attr_value 
                  FROM
                    `es_product_attr` a
                    , `es_attr` b 
                  WHERE a.`attr_id` = b.`id_attr` 
                  AND b.datatype_id NOT IN (2)
                  GROUP BY a.product_id
                    , name
                    , attr_value
                UNION ALL 
                SELECT 
                     a.product_id as product_id
                    , a.`field_name`
                    , b.value_name 
                FROM
                    es_optional_attrhead a
                    , es_optional_attrdetail b 
                WHERE a.`id_optional_attrhead` = b.`head_id` 
                GROUP BY a.product_id
                    , field_name
                    , value_name
            ) a
            WHERE product_id IN (:ids) " . $additionalString, $rsm);

        $query->setParameter('ids', $productId); 
        if($filter){
            $counter = 0;
            foreach ($parameters as $paramKey => $paramValue) {
                $query->setParameter('head'.$counter, str_replace('_', ' ', $paramKey));
                foreach ($paramValue as $key => $value) {
                    $valueName = 'headValue'.$counter.$key;
                    $query->setParameter($valueName, $value);
                }
                $counter++;
            } 
        }
        $results = $query->getResult();

        return $results;
    }

    /**
     * Get All available brands in given products
     * @param  array  $productId [description]
     * @return [type]            [description]
     */
    public function getProductBrandsByProductIds($productId = array())
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $qbResult = $qb->select('DISTINCT(b.name) as brand')
                                ->from('EasyShop\Entities\EsProduct','p')
                                ->leftJoin('EasyShop\Entities\EsBrand','b','WITH','b.idBrand = p.brand')
                                ->where(
                                        $qb->expr()->in('p.idProduct', $productId)
                                    )
                                ->getQuery();
        $result = $qbResult->getResult();
        $resultNeeded = array_map(function($value) { return $value['brand']; }, $result);

        return $resultNeeded;
    }

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

    /**
     * Returns the number of active products
     * 
     * @return int
     *
     */
    public function getActiveProductCount()
    {
        $this->em = $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('count', 'count');

        $sql = " 
          SELECT COUNT(*) as count
          FROM es_product
          WHERE is_draft = 0 AND is_delete = 0
        ";
        
        $query = $this->em->createNativeQuery($sql, $rsm);
        $result = $query->getOneOrNullResult();

        return $result['count'];
    }

    public function getProductByParameterFiltering($filteredArray,$productIds = array())
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $qbResult = $qb->select('p.idProduct')
                                ->from('EasyShop\Entities\EsProduct','p')
                                ->leftJoin('EasyShop\Entities\EsMember','m',
                                                    'WITH','p.member = m.idMember')
                                ->leftJoin('EasyShop\Entities\EsProductShippingHead','sph',
                                                    'WITH','p.idProduct = sph.product')
                                ->where('p.isDraft = 0')
                                ->andWhere('p.isDelete = 0');
 
        if(isset($filteredArray['condition']) && $filteredArray['condition'][0]){ 
            $qbResult = $qbResult->andWhere(
                                        $qb->expr()->in('p.condition', $filteredArray['condition'])
                                    );
        }
 
        if(isset($filteredArray['seller']) && $filteredArray['seller']){
            $qbResult = $qbResult->andWhere('m.username LIKE :username')
                                ->setParameter('username', '%'.$filteredArray['seller'].'%');
        }

        if(isset($filteredArray['category']) 
                && $filteredArray['category'] > 1){ 
            $categoryList = $this->em->getRepository('EasyShop\Entities\EsCat')
                                        ->getChildCategoryRecursive($filteredArray['category']);
            $qbResult = $qbResult->andWhere(
                                        $qb->expr()->in('p.cat', $categoryList)
                                    );
        }

        if(isset($filteredArray['brand']) && $filteredArray['brand'][0]){
            $qbResult = $qbResult->andWhere(
                                        $qb->expr()->in('p.brand', $filteredArray['brand'])
                                    );
        }

        if(isset($filteredArray['location']) && $filteredArray['location'][0]){ 
            $qbResult = $qbResult->andWhere(
                                        $qb->expr()->in('sph.location', $filteredArray['location'])
                                    );
        }

        if(count($productIds)>1){
            $qbResult = $qbResult->andWhere(
                                        $qb->expr()->in('p.idProduct', $productIds)
                                    );
        }

        $qbResult = $qbResult->getQuery();
        $result = $qbResult->getResult(); 
        $resultNeeded = array_map(function($value) { return $value['idProduct']; }, $result);

        return $resultNeeded;
    }
}


