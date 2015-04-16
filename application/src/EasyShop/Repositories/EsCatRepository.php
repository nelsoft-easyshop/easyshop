<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsCat;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;

class EsCatRepository extends EntityRepository
{

    /**
     * Select all category but root is not included
     * @return array
     */
    public function selectAllCategory()
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_cat','id_cat');
        $rsm->addScalarResult('parent_id','parent_id');
        $rsm->addScalarResult('slug','slug');
        $rsm->addScalarResult('name','name');
        $rsm->addScalarResult('description','description'); 

        $sql = "SELECT id_cat, parent_id, slug, name, description FROM es_cat where id_cat != :rootCategory";
        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter('rootCategory', \EasyShop\Entities\EsCat::ROOT_CATEGORY_ID);
        $categories = $query->getResult();

        return $categories;
    }

    /**
     * Get all children category recursively up to last category of the selected category
     * @param  integer $categoryId
     * @param  boolean $returnAsString
     * @return mixed
     */
    public function getChildCategoryRecursive($categoryId = \EasyShop\Entities\EsCat::ROOT_CATEGORY_ID,
                                              $returnAsString = false)
    {
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('categoryList', 'categoryList');
        $query = $this->em->createNativeQuery("
                SELECT 
                CASE
                   WHEN `GetFamilyTree` (id_cat) = '' 
                THEN :categoryId
                   ELSE CONCAT(:categoryId,',',`GetFamilyTree` (id_cat))
                END as categoryList
                FROM
                `es_cat` 
                WHERE id_cat != :rootCategory 
                AND id_cat = :categoryId ;
        ", $rsm);

        $query->setParameter('categoryId', $categoryId); 
        $query->setParameter('rootCategory', \EasyShop\Entities\EsCat::ROOT_CATEGORY_ID); 
        $results = $query->getOneOrNullResult();
        
        if($returnAsString){
            return $results['categoryList'];
        }

        return explode(',', $results['categoryList']);
    }

    /**
     * Get nested category recursively
     * @param  integer $categoryId
     * @return array
     */
    public function getChildrenWithNestedSet($categoryId = \EasyShop\Entities\EsCat::ROOT_CATEGORY_ID)
    {
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('original_category_id', 'original_category_id');
        $query = $this->em->createNativeQuery("
            SELECT 
                t1.original_category_id AS original_category_id
            FROM
                es_category_nested_set t1
                    LEFT JOIN
                es_category_nested_set t2 ON t2.original_category_id = :category_id
            WHERE
                t1.left > t2.left
                    AND t1.right < t2.right
        ", $rsm);
        $query->setParameter('category_id', $categoryId); 
        $results = $query->getArrayResult();
        $resultIds = [];
        foreach ($results as $value) {
            $resultIds[] = $value['original_category_id'];
        }
        $resultIds[] = $categoryId;

        return $resultIds;
    }

    /**
     * Get all parent of parent of the selected category
     * @param  integer $categoryId
     * @return array
     */
    public function getParentCategoryRecursive($categoryId = \EasyShop\Entities\EsCat::ROOT_CATEGORY_ID)
    {
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('idCat', 'idCat');
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('slug', 'slug');
        $rsm->addScalarResult('keywords', 'keywords');
        $rsm->addScalarResult('description', 'description');
        $rsm->addScalarResult('parent', 'parent');
        $rsm->addScalarResult('sortOrder', 'sortOrder');
        $rsm->addScalarResult('isMain', 'isMain');
             $query = $this->em->createNativeQuery("
                SELECT 
                    T2.id_cat as idCat,
                    T2.name,
                    T2.slug,
                    T2.keywords,
                    T2.description,
                    T2.parent_id as parent,
                    T2.sort_order,
                    T2.is_main
                FROM (
                    SELECT
                        @r AS _id,
                        (SELECT @r := parent_id FROM es_cat WHERE id_cat = _id) AS parent_id,
                        @l := @l + 1 AS lvl
                    FROM
                        (SELECT @r := :categoryId, @l := 0) vars,
                        es_cat h
                    WHERE @r != 1
                ) T1
                JOIN es_cat T2
                ON T1._id = T2.id_cat
                ORDER BY T1.lvl DESC
             ", $rsm);

        $query->setParameter('categoryId', $categoryId); 
        $results = $query->getResult();
        
        return $results;
    }
    
    /**
     * Get parent categories(default) of products uploaded by a specific user
     * using the adjececny list implementation
     *
     * @param integer $memberId
     * @return mixed
     */
    public function getUserCategoriesUsingAdjacencyList($memberId)
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
        $uploadsPerCategory = $query->getResult();
        return $uploadsPerCategory;
    }
        
        
    /**
     * Get parent categories(default) of products uploaded by a specific user
     * using the nested set implementation
     *
     * @param integer $memberId
     * @return mixed
     */
    public function getUserCategoriesUsingNestedSet($memberId)
    {
        $em = $this->_em;
        $parentCategories = $em->createQueryBuilder()
                                ->select('n') 
                                ->from('EasyShop\Entities\EsCategoryNestedSet','n')
                                ->innerJoin('n.originalCategory', 'c', 'WITH', 'c.idCat != 1 AND c.parent = 1')
                                ->getQuery()
                                ->getResult();
        $mainCategoryList = [];
        foreach($parentCategories as $category){
            $categoryId = $category->getOriginalCategory()->getIdCat();
            $mainCategoryList[$categoryId]['nestedTableLeft'] = $category->getLeft();
            $mainCategoryList[$categoryId]['nestedTableRight'] = $category->getRight();
        }                        
        
        $count = 1;
        $wherePartialQuery = '';
        $casePartialQuery = '';
        $bindedParameters = [];

        $numberOfMainCategories = count($mainCategoryList);
       
        $whereClauseCounter = 0;
        $caseClauseCounter = 0;
        foreach($mainCategoryList as  $index => $mainCategory){
            $casePartialQuery .= "WHEN (es_category_nested_set.left > :param".($caseClauseCounter)." AND es_category_nested_set.right < :param".($caseClauseCounter+1).") 
                                 THEN :param".($caseClauseCounter+2)." "; 
            $bindedParameters[$caseClauseCounter] = $mainCategory['nestedTableLeft'];
            $bindedParameters[$caseClauseCounter+1] = $mainCategory['nestedTableRight'];
            $bindedParameters[$caseClauseCounter+2] = $index;
            $caseClauseCounter += 3;
            $adjustedIndex = 3*$numberOfMainCategories + $whereClauseCounter;
            $wherePartialQuery .= " (es_category_nested_set.left > :param".($adjustedIndex+1)." AND es_category_nested_set.right < :param".($adjustedIndex+2).") OR";
            $bindedParameters[$adjustedIndex+1] = $mainCategory['nestedTableLeft'];
            $bindedParameters[$adjustedIndex+2] = $mainCategory['nestedTableRight'];
            $whereClauseCounter += 2;
        }
        $wherePartialQuery = rtrim($wherePartialQuery, 'OR');
        
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('parent_id','parent_id');
        $rsm->addScalarResult('children','children');
        $sql = "SELECT 
                    GROUP_CONCAT(es_category_nested_set.original_category_id) as children, 
                    CASE
                        ".$casePartialQuery." 
                    END as parent_id
                FROM es_category_nested_set
                WHERE ".$wherePartialQuery." GROUP BY parent_id";
              
        $query = $em->createNativeQuery($sql, $rsm);
        foreach($bindedParameters as $index => $param){
            $query->setParameter('param'.$index, $param);
        }
        $mainCategoryChildrenList = $query->getResult();
        
        $reindexedMainCategoryChildrenList = [];
     
        foreach($mainCategoryChildrenList as $mainCategory){
            $reindexedMainCategoryChildrenList[$mainCategory['parent_id']] = $mainCategory;
        }
        $mainCategoryChildrenList = $reindexedMainCategoryChildrenList;
        
        foreach($mainCategoryList as $categoryId => $mainCategory){
            if(!isset($mainCategoryChildrenList[$categoryId])){
                $mainCategoryChildrenList[$categoryId] = [
                    'children' => '', 
                    'parent_id' => $categoryId,
                ];
            }
        } 
       
        $casePartialQuery = "";
        $bindParameters = [];
        foreach($mainCategoryChildrenList as  $index => $childList){
            $categoryString =  rtrim($childList['parent_id'].','.$childList['children'],',');
            $childrenArray = explode(',',$categoryString);
            $qmarks = implode(',', array_fill(0, count($childrenArray), '?'));
            $casePartialQuery .= " WHEN es_product.cat_id IN (".$qmarks.") THEN ? "; 
            $bindParameters = array_merge($bindParameters, $childrenArray);
            $bindParameters[] = $childList['parent_id'];
        }
        
        $sql = "
                SELECT A.productCount, 
                       A.parent_id, 
                       A.category_id as id_cat, 
                       IF(parent.id_cat != 1,parent.name,'null') as name,
                       IF(parent.id_cat != 1, parent.slug, 'null') as slug,
                       es_cat_img.path as image  
                FROM 
                (
                    SELECT 
                        count(es_product.id_product) as productCount,
                        CASE
                            ".$casePartialQuery."
                            ELSE 1
                        END as parent_id,
                        es_product.cat_id as category_id
                    FROM es_product 
                    WHERE es_product.is_draft = 0 AND es_product.is_delete = 0 AND es_product.member_id = ?
                    GROUP BY es_product.cat_id
                ) A
                LEFT JOIN es_cat as parent ON parent.id_cat = A.parent_id
                LEFT JOIN es_cat_img ON es_cat_img.id_cat = parent.id_cat
        ";
        

        $bindParameters[] = $memberId;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('productCount','prd_count');
        $rsm->addScalarResult('parent_id','parent_cat');
        $rsm->addScalarResult('id_cat','cat_id');
        $rsm->addScalarResult('name','p_cat_name');
        $rsm->addScalarResult('slug','p_cat_slug');
        $rsm->addScalarResult('image','p_cat_img');
        $query = $em->createNativeQuery($sql, $rsm);
        $count = 1;
        foreach($bindParameters as $param){
            $query->setParameter($count++, $param);
        }
        $uploadsPerCategory = $query->getResult();

        return $uploadsPerCategory;
    }

    
    /**
     * Get the first level categories
     *
     * @return EasyShop\Entities\EsCat[]
     */
    public function getParentCategories($limit = null)
    {
        $em = $this->_em;
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->select('c')
                     ->from('EasyShop\Entities\EsCat', 'c')
                     ->where('c.parent = :parentId')
                     ->andWhere('c.idCat != :parentId')
                     ->setParameter('parentId', \EasyShop\Entities\EsCat::ROOT_CATEGORY_ID);

        if ($limit !== null) {
            $queryBuilder->setMaxResults($limit);
        }

        $parentCategories = $queryBuilder->getQuery()
                                         ->getResult();

        return $parentCategories;
    }

    /**
     * Get parent category using nested set table
     * @param  integer $categoryId
     * @return array
     */
    public function getAncestorsWithNestedSet($categoryId = \EasyShop\Entities\EsCat::ROOT_CATEGORY_ID)
    {
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('original_category_id', 'original_category_id');
        $query = $this->em->createNativeQuery("
            SELECT 
                t1.original_category_id
            FROM
                es_category_nested_set t0
                    LEFT JOIN
                es_category_nested_set t1 ON t1.left < t0.left
                    AND t1.right > t0.right
            WHERE
                t0.original_category_id = :category_id
                    AND t1.original_category_id != :root_category
        ", $rsm);
        $query->setParameter('category_id', $categoryId); 
        $query->setParameter('root_category', \EasyShop\Entities\EsCat::ROOT_CATEGORY_ID); 
        $results = $query->getArrayResult();
        $resultIds = [];
        foreach ($results as $value) {
            $resultIds[] = $value['original_category_id'];
        }

        return $resultIds;
   } 

}



