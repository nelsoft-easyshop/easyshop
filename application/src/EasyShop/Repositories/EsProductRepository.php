<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsProduct; 
use EasyShop\Entities\EsProductImage;
use EasyShop\Entities\EsBrand;
use EasyShop\Entities\EsProductItem;
use EasyShop\Entities\EsProductItemAttr;
use EasyShop\Entities\EsOptionalAttrdetail;
use EasyShop\Entities\EsOptionalAttrhead;
use EasyShop\Entities\EsProductShippingHead;
use EasyShop\Entities\EsProductShippingDetail;
use Doctrine\ORM\Tools\Pagination\Paginator;

class EsProductRepository extends EntityRepository
{
    /**
     * Find all records in database related to string given in parameters
     * @param  array  $stringCollection 
     * @return object
     */
    public function findByKeyword($stringCollection = array(),$productIds = array(),$booleanLimit = false)
    {
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult( 'idProduct', 'idProduct');
        $limitString = ($booleanLimit) ? "LIMIT 500" : "";
        $query = $this->em->createNativeQuery("
            SELECT DISTINCT(`id_product`) as idProduct
                , `name` 
                , `weight`
            FROM (
                    SELECT 
                        (MATCH (`name`) AGAINST (:param0 IN BOOLEAN MODE) * 10) +
                        (MATCH (`search_keyword`) AGAINST (:param0 IN BOOLEAN MODE) * 1.5) +
                        (MATCH (`name`) AGAINST (:param1 IN BOOLEAN MODE) * 10) +
                        (MATCH (`search_keyword`) AGAINST (:param1 IN BOOLEAN MODE) * 1.5) +
                        (MATCH (`name`) AGAINST (:param2 IN BOOLEAN MODE) * 15) +
                        (MATCH (`search_keyword`) AGAINST (:param2 IN BOOLEAN MODE) * 2) +
                        (MATCH (b.`store_name`) AGAINST (:param1 IN BOOLEAN MODE) * 10)  
                         AS weight,
                        id_product,`name`,price,brief,a.`slug`,`condition`,startdate, enddate,is_promote,promo_type,discount
                        ,`is_sold_out`
                    FROM es_product a,
                         es_member b
                    WHERE is_delete = 0 
                            AND is_draft = 0
                            AND a.member_id = b.id_member
                            AND a.id_product in (:ids)
                            AND (
                                MATCH (b.`store_name`) AGAINST (:param2 IN BOOLEAN MODE)
                                OR MATCH (a.`search_keyword`) AGAINST (:param2 IN BOOLEAN MODE)
                                OR MATCH (a.`name`) AGAINST (:param2 IN BOOLEAN MODE)

                                OR MATCH (b.`store_name`) AGAINST (:param0 IN BOOLEAN MODE)
                                OR MATCH (a.`search_keyword`) AGAINST (:param0 IN BOOLEAN MODE)
                                OR MATCH (a.`name`) AGAINST (:param0 IN BOOLEAN MODE)

                                OR MATCH (b.`store_name`) AGAINST (:param1 IN BOOLEAN MODE)
                                OR MATCH (a.`name`) AGAINST (:param1 IN BOOLEAN MODE)
                                OR MATCH (a.`search_keyword`) AGAINST (:param1 IN BOOLEAN MODE)
                            )
                    $limitString
                ) as score_table
            HAVING weight > 0
            ORDER BY weight DESC,name ASC
        ", $rsm);

        $query->setParameter('param0', $stringCollection[0]);
        $query->setParameter('param1', $stringCollection[1]);
        $query->setParameter('param2', $stringCollection[2]); 
        $query->setParameter('ids', $productIds);
        $results = $query->execute();

        return $results;
    }

    /**
     * Get all product details with given product id
     * @param  array   $productIds
     * @param  integer $offset
     * @param  integer $perPage
     * @param  boolean $applyLimit
     * @return mixed
     */
    public function getProductDetailsByIds($productIds = array(),$offset = 0,$perPage = 1,$applyLimit = TRUE)
    {   
        if(!empty($productIds)){
                $this->em =  $this->_em;

                $sql = "
                    SELECT 
                        p
                    FROM 
                        EasyShop\Entities\EsProduct p 
                    WHERE p.idProduct IN (:ids)
                ";
                $query = $this->em->createQuery($sql)
                                    ->setParameter('ids', $productIds);
                if($applyLimit){
                    $query->setFirstResult($offset*$perPage)
                        ->setMaxResults($perPage);
                }
                
                $results = $query->getResult();

                return $results;
            }
            
        return array();
    }

    /**
     * Get all attributes of given productids
     * @param  array  $productIds
     * @return array
     */
    public function getAttributesByProductIds($productIds = [],$filter = false,$additionalString = "",$parameters = [])
    {
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('product_id', 'product_id');

        if($filter){
            $selectString = 'COUNT(*) as cnt,product_id';
        }
        else{
            $selectString = 'name,attr_value,head_id,detail_id,is_other,price,image_id';
            $rsm->addScalarResult('name', 'head');
            $rsm->addScalarResult('attr_value', 'value');
            $rsm->addScalarResult('head_id', 'head_id');
            $rsm->addScalarResult('detail_id', 'detail_id');
            $rsm->addScalarResult('is_other', 'is_other');
            $rsm->addScalarResult('price', 'price');
            $rsm->addScalarResult('image_id', 'image_id');
            $rsm->addScalarResult('type', 'type');
            $rsm->addScalarResult('datatype_id', 'datatype_id');
        }

        $query = $this->em->createNativeQuery("
            SELECT ".$selectString." FROM (
                SELECT 
                    a.product_id as product_id
                    , b.name AS name
                    , a.attr_value
                    , a.attr_id as head_id
                    , a.id_product_attr as detail_id
                    , '0' as is_other
                    , a.attr_price as price
                    , '0' as image_id
                    ,'specific' as 'type'
                    , b.datatype_id as 'datatype_id'
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
                    , b.head_id
                    , b.id_optional_attrdetail as detail_id
                    , '1' as is_other
                    , b.value_price as price
                    , b. product_img_id as price
                    , 'option' as 'type'
                    , '0' as 'datatype_id'
                FROM
                    es_optional_attrhead a
                    , es_optional_attrdetail b 
                WHERE a.`id_optional_attrhead` = b.`head_id` 
                GROUP BY a.product_id
                    , field_name
                    , value_name
            ) a
            WHERE product_id IN (:ids) " . $additionalString, $rsm);

        $query->setParameter('ids', $productIds); 
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
     * @param  array  $productIds
     * @return mixed
     */
    public function getProductBrandsByProductIds($productIds = array())
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $qbResult = $qb->select('DISTINCT(b.name) as brand')
                                ->from('EasyShop\Entities\EsProduct','p')
                                ->leftJoin('EasyShop\Entities\EsBrand','b','WITH','b.idBrand = p.brand')
                                ->where(
                                        $qb->expr()->in('p.idProduct', $productIds)
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
    public function getProductAttributeDetailByName($productId, $fieldName = null, $fieldValue = null, $fieldPrice = null)
    {
    
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('name', 'attr_name');
        $rsm->addScalarResult('attr_value', 'attr_value');
        $rsm->addScalarResult('attr_price', 'attr_price');
        $rsm->addScalarResult('attr_id', 'attr_id');
        $rsm->addScalarResult('image_id', 'image_id');
        $rsm->addScalarResult('image_path', 'image_path');
        $rsm->addScalarResult('is_other', 'is_other');
        $rsm->addScalarResult('type', 'type');
        $rsm->addScalarResult('datatype_id', 'datatype_id');
            
        $sql1 = "     
            SELECT 
                h.field_name AS `name`,
                d.value_name AS attr_value, 
                d.value_price AS attr_price,
                d.id_optional_attrdetail as attr_id, 
                d.product_img_id as image_id, 
                COALESCE(i.product_image_path,'') AS image_path, 
                '1' as is_other,
                'option' as 'type',
                '0' as 'datatype_id'
            FROM es_optional_attrhead h 
            LEFT JOIN es_optional_attrdetail d ON h.id_optional_attrhead = d.head_id
            LEFT JOIN es_product_image i ON d.product_img_id = i.id_product_image
            WHERE h.product_id=:id 
        ";
        
        $sql2 = "
            SELECT 
                b.name, 
                a.attr_value, 
                a.attr_price,
                a.id_product_attr, 
                '0', 
                '', 
                '0',
                'specific' as 'type',
                b.datatype_id as 'datatype_id'
            FROM es_product_attr a 
            LEFT JOIN  es_attr b ON a.attr_id = b.id_attr
            WHERE a.product_id =:id 
        ";
        
        if($fieldName !== null || $fieldValue !== null){ 
            $sql1 .= " AND h.`field_name`= :attr  AND d.`value_name` = :attr_value"; 
            $sql2 .= " AND b.`name`= :attr  AND a.`attr_value` = :attr_value";
        }

        if($fieldPrice !== null){
            $fieldPriceFilter = " AND d.value_price = :attr_price";
            $sql1 .= $fieldPriceFilter;
            $fieldPriceFilter = " AND a.attr_price = :attr_price";
            $sql2 .= $fieldPriceFilter;
        }
        
        $sql = $sql1." UNION ".$sql2;
        
        $query = $this->em->createNativeQuery($sql,$rsm);
        $query->setParameter('id', $productId);

        if($fieldName !== null || $fieldValue !== null){
            $query->setParameter('attr', $fieldName);
            $query->setParameter('attr_value', $fieldValue);
        }

        if($fieldPrice !== null){
            $query->setParameter('attr_price', $fieldPrice);
        }
        
        if($fieldName !== null || $fieldValue !== null){ 
            return $query->getOneOrNullResult();
        }
        
        return $query->getResult();
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
            if(count($result) === 0){
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

    /**
     *  Get parent categories(default) of products uploaded by a specific user
     *
     *  @return array
     */
    public function getUserProductParentCategories($memberId)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('parent_cat','parent_cat');
        $rsm->addScalarResult('cat_id','cat_id');
        $rsm->addScalarResult('prd_count','prd_count');
        $rsm->addScalarResult('p_cat_name','p_cat_name');
        $rsm->addScalarResult('p_cat_slug','p_cat_slug');
        $rsm->addScalarResult('p_cat_img','p_cat_img');

        $sql = "call `es_sp_vendorProdCatDetails`(:member_id)";
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter('member_id', $memberId);

        return $query->getResult();
    }

    /**
     * Get product by default parameters
     * @param  array $filterArray
     * @param  array  $productIds
     * @return array
     */
    public function getProductByParameterFiltering($filterArray,
                                                   $productIds = [],
                                                   $excludePromos = [],
                                                   $excludeProduct = [])
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $qbResult = $qb->select('DISTINCT p.idProduct')
                                ->from('EasyShop\Entities\EsProduct','p')
                                ->leftJoin('EasyShop\Entities\EsMember','m',
                                                    'WITH','p.member = m.idMember')
                                ->leftJoin('EasyShop\Entities\EsProductShippingHead','sph',
                                                    'WITH','p.idProduct = sph.product')
                                ->leftJoin('EasyShop\Entities\EsBrand','b',
                                                    'WITH','b.idBrand = p.brand')
                                ->where('p.isDraft = 0')
                                ->andWhere('p.isDelete = 0');
 
        if(isset($filterArray['condition']) && $filterArray['condition'][0]){ 
            $qbResult = $qbResult->andWhere(
                                        $qb->expr()->in('p.condition', $filterArray['condition'])
                                    );
        }
 
        if(isset($filterArray['seller']) && $filterArray['seller']){
            $sellerString = $filterArray['seller'];
            if (strpos($filterArray['seller'],'seller:') !== false) {
                $sellerString = str_replace("seller:","",$filterArray['seller']);
            }
            $qbResult = $qbResult->andWhere('m.username LIKE :username')
                                ->setParameter('username', $sellerString);
        }

        if(isset($filterArray['category']) 
                && $filterArray['category'] > 1){ 
            $categoryList = $this->em->getRepository('EasyShop\Entities\EsCat')
                                        ->getChildCategoryRecursive($filterArray['category']);
            $qbResult = $qbResult->andWhere(
                                        $qb->expr()->in('p.cat', $categoryList)
                                    );
        }

        if(isset($filterArray['brand']) && $filterArray['brand'][0]){
            $qbResult = $qbResult->andWhere(
                                        $qb->expr()->in('b.name', $filterArray['brand'])
                                    );
        }

        if(isset($filterArray['location']) && $filterArray['location'][0]){ 
            $qbResult = $qbResult->andWhere(
                                        $qb->expr()->in('sph.location', $filterArray['location'])
                                    );
        }

        if(!empty($productIds)){
            $qbResult = $qbResult->andWhere(
                                        $qb->expr()->in('p.idProduct', $productIds)
                                    );
        }

        if(!empty($excludePromos)){
            $qbResult = $qbResult->andWhere(
                                        $qb->expr()->notIn('p.promoType', $excludePromos)
                                    );
        }

        if(!empty($excludeProduct)){
            $qbResult = $qbResult->andWhere(
                                        $qb->expr()->notIn('p.slug', $excludeProduct)
                                    );
        }

        if(isset($filterArray['sortby'])){

            $order = "DESC";
            if(isset($filterArray['sorttype']) 
                && strtoupper($filterArray['sorttype']) == "ASC"){
                $order = "ASC";
            }

            switch(strtoupper($filterArray['sortby'])){
                case "NEW":
                    $qbResult = $qbResult->orderBy('p.lastmodifieddate', $order);
                    break;
                case "HOT":
                    $qbResult = $qbResult->orderBy('p.isHot', $order)
                                         ->addOrderBy(' p.clickcount',$order);
                    break;
                case "NAME":
                    $qbResult = $qbResult->orderBy('p.name', $order);
                    break;
                default:
                    $qbResult = $qbResult->orderBy('p.clickcount', $order);
                    break;
            }
        }

        $qbResult = $qbResult->getQuery();
        $result = $qbResult->getResult(); 
        $resultNeeded = array_map(function($value) { return $value['idProduct']; }, $result);
        
        return $resultNeeded; 
    }

    /**
     *  Count Not Custom categorized products
     *
     *  @param integer $memberId
     *  @param array $catId
     *
     *  @return integer 
     */
    public function countNotCustomCategorizedProducts($memberId, $catId)
    {
        $em = $this->_em;
        $result = array();

        // Generate category IN condition
        $catCount = count($catId);
        $arrCatParam = array();
        for($i=1;$i<=$catCount;$i++){
            $arrCatParam[] = ":i" . $i;
        }
        $catInCondition = implode(',',$arrCatParam);

        $dql = "
            SELECT COUNT(p.idProduct)
            FROM EasyShop\Entities\EsProduct p
            WHERE p.idProduct NOT IN (
                    SELECT p2.idProduct
                    FROM EasyShop\Entities\EsMemberProdcat pc
                    JOIN pc.memcat mc
                    JOIN pc.product p2
                    WHERE mc.member = :member_id
                )
                AND p.member = :member_id
                AND p.cat IN ( " . $catInCondition . " )
                AND p.isDelete = 0
                AND p.isDraft = 0 ";

        $query = $em->createQuery($dql)
                    ->setParameter('member_id', $memberId);

        for($i=1;$i<=$catCount;$i++){
            $query->setParameter('i'.$i, $catId[$i-1]);
        }

        return $query->getSingleScalarResult();
    }

    /**
     *  Get user products that have no assigned custom category
     *  Returns Product IDs ONLY!
     *
     *  @param integer $memberId
     *  @param array $catId
     *
     *  @return array
     */
    public function getPagedNotCustomCategorizedProducts($memberId, $catId, $productLimit=12, $page=0, $orderBy=array("clickcount"=>"DESC") )
    {
        $em = $this->_em;
        $result = array();

        // Generate category IN condition
        $catCount = count($catId);
        $arrCatParam = array();
        for($i=1;$i<=$catCount;$i++){
            $arrCatParam[] = ":i" . $i;
        }
        $catInCondition = implode(',',$arrCatParam);

        // Generate Order by condition
        $orderCondition = "";
        foreach($orderBy as $column=>$order){
            $orderCondition .= "p." . $column . " " . $order . ", ";
        }
        $orderCondition = rtrim($orderCondition, ", ");

        $dql = "
            SELECT p
            FROM EasyShop\Entities\EsProduct p
            WHERE p.idProduct NOT IN (
                    SELECT p2.idProduct
                    FROM EasyShop\Entities\EsMemberProdcat pc
                    JOIN pc.memcat mc
                    JOIN pc.product p2
                    WHERE mc.member = :member_id
                )
                AND p.member = :member_id
                AND p.cat IN ( " . $catInCondition . " )
                AND p.isDelete = 0
                AND p.isDraft = 0 
                ORDER BY " . $orderCondition;

        $query = $em->createQuery($dql)
                    ->setParameter('member_id', $memberId)
                    ->setFirstResult($page)
                    ->setMaxResults($productLimit);

        for($i=1;$i<=$catCount;$i++){
            $query->setParameter('i'.$i, $catId[$i-1]);
        }

        $rawResult = new Paginator($query, $fetchJoinCollection = TRUE);

        foreach( $rawResult as $row ){
            $result[] = $row->getIdProduct();
        }

        return $result;
    }

    /**
     *  Get user products that have no assigned custom category
     *  Returns Product IDs ONLY!
     *
     *  @param integer $memberId
     *  @param array $catId
     *  @param string $condition
     *
     *  @return array
     */
    public function getAllNotCustomCategorizedProducts($memberId, $catId, $condition){
        $em = $this->_em;
        $result = array();

        // Generate category IN condition
        $catCount = count($catId);
        $arrCatParam = array();
        for($i=1;$i<=$catCount;$i++){
            $arrCatParam[] = ":i" . $i;
        }
        $catInCondition = implode(',',$arrCatParam);

        $dql = "
            SELECT p.idProduct
            FROM EasyShop\Entities\EsProduct p
            WHERE p.idProduct NOT IN (
                    SELECT p2.idProduct
                    FROM EasyShop\Entities\EsMemberProdcat pc
                    JOIN pc.memcat mc
                    JOIN pc.product p2
                    WHERE mc.member = :member_id
                )
                AND p.member = :member_id
                AND p.cat IN ( " . $catInCondition . " )
                AND p.isDelete = 0
                AND p.isDraft = 0 ";

        if($condition !== "") {
            $dql .= "AND p.condition = :condition";
        }
        $query = $em->createQuery($dql)
                    ->setParameter('member_id', $memberId);

        if($condition !== "") {
            $query->setParameter("condition", $condition);
        }

        for($i=1;$i<=$catCount;$i++){
            $query->setParameter('i'.$i, $catId[$i-1]);
        }

        $rawResult = $query->getResult();

        foreach( $rawResult as $row ){
            $result[] = $row['idProduct'];
        }

        return $result;
    }

    /**
     * Get popular items by seller or category based on click count
     * @param  $offset   [description]
     * @param  $perPage  [description]
     * @param  integer $seller   [description]
     * @param  integer[] $category [description]
     * @return mixed
     */
    public function getPopularItemByCategory($categoryId, $offset = 0, $perPage = 1)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $qbResult = $qb->select('p')
                       ->from('EasyShop\Entities\EsProduct','p')
                       ->where('p.isDraft = 0')
                       ->andWhere('p.isDelete = 0')
                       ->andWhere(
                            $qb->expr()->in('p.cat', $categoryId)
                        )
                       ->addOrderBy(' p.clickcount','DESC')
                       ->setFirstResult($offset)
                       ->setMaxResults($perPage)
                       ->getQuery();
       
        $result = $qbResult->getResult();

        return $result;
    }

    /**
     * Delete products that do not have images inside admin folder
     * @param  integer $id
     */    
    public function deleteProductFromAdmin($id)
    {
        $this->em =  $this->_em;
         
        $query = $this->em->createQuery("DELETE FROM EasyShop\Entities\EsProduct e 
            WHERE e.idProduct = ?8");
        $query->setParameter(8, $id);
        $query->execute();       

    }

    /**
     * Get the seller of a product. Used for strange cases where the member cannot
     * be retrieved from the product object
     *
     * @param integer $productId
     * @return EasyShop\Entities\EsMember
     */
    public function getSeller($productId)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();

        $rsm->addScalarResult('member_id', 'member_id');
        $query = $this->em->createNativeQuery("
            SELECT `member_id` from es_product WHERE id_product = :productId
        ", $rsm);
        $query->setParameter('productId', $productId);
        $result = $query->execute();  
        
        $seller = $em->getRepository('EasyShop\Entities\EsMember')
                     ->find($result[0]['member_id']);
        return $seller;

    }
    
    /**
     * Returns the raw promo fields of a product. This uses native sql for efficiency.
     * This is primarily used for mass evaluation of the promo price. Hydrating EsProduct's
     * takes up too much CPU for this. Returns NULL if the product is not found.
     *
     * @param integer $productId
     * @return mixed
     */
    public function getRawProductPromoDetails($productId)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();

        $rsm->addScalarResult('price', 'price');
        $rsm->addScalarResult('discount', 'discount');
        $rsm->addScalarResult('promo_type', 'promo_type');
        $rsm->addScalarResult('is_promote', 'is_promote');
        $rsm->addScalarResult('startdate', 'startdate');
        $rsm->addScalarResult('enddate', 'enddate');
        $query = $em->createNativeQuery("
            SELECT `price`, `discount`, `promo_type`, `is_promote`, `startdate`, `enddate` from es_product WHERE id_product = :productId
        ", $rsm);
        $query->setParameter('productId', $productId);
        $result = $query->execute();  
        return isset($result[0]) ? $result[0] : NULL;
    }
    
    /**
     * Get product bank and billing information
     * @param  integer $memberId
     * @param  integer $productId
     * @return object
     */
    public function getProductBillingInfo($memberId, $productId)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $queryBuilder = $qb->select('bi.bankAccountName,
                                     bi.bankAccountNumber,
                                     bank.bankName')
                           ->from('EasyShop\Entities\EsProduct','p') 
                           ->innerJoin('EasyShop\Entities\EsBillingInfo', 'bi', 'WITH',
                                $qb->expr()->andX(
                                    $qb->expr()->eq('p.billingInfoId', 'bi.idBillingInfo'),
                                    $qb->expr()->eq('bi.member', ':member_id'),
                                    $qb->expr()->eq('p.idProduct', ':product_id')
                                )
                            )
                           ->innerJoin('EasyShop\Entities\EsBankInfo', 'bank', 'WITH', 'bi.bankId = bank.idBank')
                           ->setParameter('member_id', $memberId) 
                           ->setParameter('product_id', $productId)
                           ->getQuery();

        $result = $queryBuilder->getOneOrNullResult();

        return $result;
    }

    /**
     * Get shipping details of the given product
     * @param  integer $productId
     * @return mixed
     */
    public function getProductShippingDetails($productId)
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();

        $rsm->addScalarResult('id_product_item', 'id_product_item');
        $rsm->addScalarResult('id_location', 'id_location');
        $rsm->addScalarResult('price', 'price');
        $rsm->addScalarResult('product_item_id', 'product_item_id');
        $rsm->addScalarResult('location', 'location');
        $rsm->addScalarResult('id_shipping_detail', 'id_shipping_detail');
        $query = $em->createNativeQuery("
            SELECT pi.id_product_item, COALESCE(loc.id_location,'0') as id_location, loc.location, COALESCE(sh.price,'0') as price, sd.id_shipping_detail
            FROM es_product_item pi
            LEFT JOIN es_product_shipping_detail sd
                ON sd.product_item_id = pi.id_product_item
            LEFT JOIN es_product_shipping_head sh
                ON sh.id_shipping = sd.shipping_id
            LEFT JOIN es_location_lookup loc
                ON sh.location_id = loc.id_location
            WHERE pi.product_id = :productId
        ", $rsm);
        $query->setParameter('productId', $productId);
        $result = $query->execute();

        return $result;
    }

    /**
     * Get product reccomended based on category
     * @param  integer $productId
     * @param  integer $categoryId
     * @param  integer $limit
     * @return object
     */
    public function getRecommendedProducts($productId, $categoryId, $limit = null)
    {
        $this->em =  $this->_em;
        $queryBuilder = $this->em->createQueryBuilder();
        $qbResult = $queryBuilder->select('p')
                                 ->from('EasyShop\Entities\EsProduct','p')
                                 ->where('p.cat = :category')
                                 ->andWhere("p.idProduct != :productId")
                                 ->andWhere("p.isDraft = :isDraft")
                                 ->andWhere("p.isDelete = :isDelete")
                                 ->setParameter('productId',$productId)
                                 ->setParameter('category',$categoryId)
                                 ->setParameter('isDraft',0)
                                 ->setParameter('isDelete',0)
                                 ->orderBy('p.clickcount', 'DESC')
                                 ->getQuery();;

        if($limit){
            $queryBuilder->setMaxResults($limit);
        }

        $result = $qbResult->getResult(); 

        return $result;
    }
}


