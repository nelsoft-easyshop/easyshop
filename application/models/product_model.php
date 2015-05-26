<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class product_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('product');
        $this->load->library("xmlmap");
        $this->load->library("parser");
        $this->load->config("promo");
        $this->load->library('parser');
    }

    # the queries directory -- application/resources/sql/product.xml

    /**
     * Get all category available in database
     * @return array
     */
    public function selectAllCategory()
    {
        $query = 'SELECT id_cat,parent_id,slug,name,description FROM es_cat where id_cat not in (1)';
        $sth = $this->db->conn_id->prepare($query);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    /**
     *  Get category details by using category ID
     *  @param integer $id
     */
    public function selectCategoryDetails($id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'selectCategoryDetails');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id_cat', $id);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row[0];
    }

    /**
     *  Get all childrden category of the selected category
     *  @param integer $id
     *  @param bool $is_admin
     *  @return ARRAY()
     */
    public function getDownLevelNode($id, $is_admin = false) # get all down level category on selected category from database
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'selectDownLevel');
        $protected_categories = array();
        if(!$is_admin){
            $this->config->load('protected_category', TRUE);
            $protected_categories = $this->config->config['protected_category'];
            $qmarks = implode(',', array_fill(0, count($protected_categories), '?'));
            $query = $query.' AND id_cat NOT IN ('.$qmarks.') AND id_cat != 1 ORDER BY sort_order ASC';
        }

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindValue(1, $id, PDO::PARAM_INT);
        $k = 1;
        foreach ($protected_categories as $x){
            $sth->bindValue(($k+1), $x, PDO::PARAM_INT);
            $k++;
        }
        $sth->execute();
        $row = $sth->fetchAll();

        return $row;
    }

    /**
     *  Get all children category recursively up to last category of the selected category
     *  @param interger $id
     *  @return ARRAY()
     */
    public function selectChild($id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'selectChild');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':cat_id', $id);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_COLUMN, 0);


        if (isset($row[0])){ // Added - Rain 02/25/14
            return explode(',', $row[0]);
        }
    }

    /**
     *  Get parent recursively up to main category of the selected category
     *  @param integer $id
     *  @return ARRAY()
     */
    public function getParentId($id)
    {

        $query = $this->xmlmap->getFilenameID('sql/product','getParent');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id);
        $sth->execute();

        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    function getAttributesByParent($parents) # get all attributes from all parents from to the last selected category
    {
        $array = $parents;

        $keys = array();

        for ($i=0 ; $i < sizeof($parents) ; $i++ ) {
            $keys[] = $parents[$i]['id_cat'];
        }
        $value = implode(',',$keys);

        $query = "
        SELECT DISTINCT
            a.name AS cat_name
            , a.id_attr
            , a.attr_lookuplist_id
            , b.name AS input_type
            , c.name AS input_name
        FROM
            es_attr a
            , es_datatype b
            , es_attr_lookuplist c
        WHERE a.datatype_id = b.id_datatype
            AND a.attr_lookuplist_id = c.id_attr_lookuplist
            AND a.cat_id IN (" .$value .")
            GROUP BY cat_name, a.id_attr, a.attr_lookuplist_id, input_type, input_name
        ORDER BY cat_name ASC ";

        $sth = $this->db->conn_id->prepare($query);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }


    function getProductAttributesByHead($productid,$head)
    {

        $query = "
        SELECT
            b.value_name
            , b.`value_price`
            ,  IF(b.product_img_id = '0', '', (SELECT `product_image_path` FROM `es_product_image` WHERE `id_product_image` = b.product_img_id)) AS img_path
        FROM
            `es_optional_attrhead` a
            , `es_optional_attrdetail` b
        WHERE a.`id_optional_attrhead` = b.`head_id`
        AND a.`product_id` = :productid
        AND a.`field_name` = :head

        UNION DISTINCT
        SELECT
            b.attr_value
            ,'0.00'
            ,''
        FROM
            es_attr a
            , es_product_attr b
        WHERE a.`id_attr` = b.`attr_id`
        AND b.product_id = :productid
        AND a.name = :head
        ";

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':productid',$productid,PDO::PARAM_INT);
        $sth->bindParam(':head',$head,PDO::PARAM_STR);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $row;

    }

    function getItemAttributes($itemId){
       $query = $this->xmlmap->getFilenameID('sql/product','getItemAttributes');
       $sth = $this->db->conn_id->prepare($query);
       $sth->bindParam(':itemId',$itemId);
       $sth->execute();
       $row = $sth->fetchAll(PDO::FETCH_ASSOC);

       return $row;
    }

    function getLookItemListById($id) # getting item list from database. EG: Color -- (White,Blue,Yellow)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','getLookupListItem');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    function getSlug($id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'getSlugByID');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        return $row['slug'];
    }

    /*
        *   SET second parameter to false to prevent increment of click count
        */

    function getProductBySlug($slug, $add_click_count = true)
    {
        if($add_click_count){
            $query = $this->xmlmap->getFilenameID('sql/product', 'getProductBySlug');
        }else{
            $query = $this->xmlmap->getFilenameID('sql/product', 'getProductBySlugNoIncrement');
        }
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':slug',$slug);
        $sth->execute();
        $product = $sth->fetch(PDO::FETCH_ASSOC);
        if(intval($product['o_success']) !== 0){
        
            if( !$product['storename'] || strlen(trim($product['storename'])) === 0){
                $product['storename'] = $product['sellerusername'];
            }
            
            if(strlen(trim($product['userpic']))===0)
                $product['userpic'] = 'assets/user/default';
            if(intval($product['brand_id'],10) === 1)
                $product['brand_name'] = ($product['custombrand']!=='')?$product['custombrand']:'Custom brand';
            applyPriceDiscount($product);
            if(isset($product['product_image_path'])){
                $temp = array($product);
                explodeImagePath($temp);
                $product = $temp[0];
            }
        }
        return $product;
    }



    /*
        * Feb 20, 2014 Edit: Enclosed name key with single quotes. This is to prevent the id keys from
        * being mixed-up with numeric name keys when using $key = 'ALL'
        */
    function getProductAttributes($id, $key = 'ALL') # getting the product attribute using product ID
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'getProductAttributes');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

        $data = array();

        foreach($rows as $row){
            if((!array_key_exists("'".$row['name']."'", $data))&&(($key === 'NAME')||($key === 'ALL')))
                $data["'".$row['name']."'"] = array();
            if((!array_key_exists($row['name_id'], $data))&&(($key === 'ID')||($key === 'ALL')))
                $data[$row['name_id']] = array();

            $temp = array(0=>$row);
            explodeImagePath($temp,true);
            $row = $temp[0];

            if($key === 'NAME')
                array_push($data["'".$row['name']."'"],array('value' => $row['attr_value'], 'value_id' => $row['attr_value_id'],  'price'=>$row['attr_price'],'img_path'=>$row['path'], 'img_file'=>$row['file'], 'type'=>$row['type'], 'img_id' => $row['img_id'], 'datatype' => $row['datatype_id']  ));
            else if($key === 'ID')
                array_push($data[$row['name_id']],array('value' => $row['attr_value'], 'value_id' => $row['attr_value_id'],  'price'=>$row['attr_price'],'img_path'=>$row['path'], 'img_file'=>$row['file'], 'type'=>$row['type'], 'img_id' => $row['img_id'], 'datatype' => $row['datatype_id']   ));
            else{
                array_push($data["'".$row['name']."'"],array('value' => $row['attr_value'], 'value_id' => $row['attr_value_id'],  'price'=>$row['attr_price'],'img_path'=>$row['path'], 'img_file'=>$row['file'], 'type'=>$row['type'], 'img_id' => $row['img_id'], 'datatype' => $row['datatype_id']   ));
                array_push($data[$row['name_id']],array('value' => $row['attr_value'], 'value_id' => $row['attr_value_id'],  'price'=>$row['attr_price'],'img_path'=>$row['path'], 'img_file'=>$row['file'], 'type'=>$row['type'], 'img_id' => $row['img_id'], 'datatype' => $row['datatype_id']   ));
            }
        }
        return $data;
    }

    /*
        *  Combines similar names indexes in the same array element
        *  Attributes should be indexed by "name" otherwise an empty array is returned
        *  Case is ignored.
        */
    function implodeAttributesByName($attributes){
        $data = array();
        foreach($attributes as $key=>$row){
            if(!is_string($key)){
                return array();
            }
            else{
                if(!array_key_exists(strtolower($key), $data)){
                    $data[strtolower($key)] = array();
                }
                foreach($row as $x){
                    array_push($data[strtolower($key)],$x);
                }
            }
        }
        return $data;
    }


    function getProductImages($id, $getById = false) # getting the product image using product ID
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'getProductImages');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

        explodeImagePath($rows);

        if($getById){
            $rowsById = array();
            foreach($rows as $row){
                $rowsById[$row['id_product_image']] = $row;
            }
            $rows = $rowsById;
        }
        return $rows;
    }



    function getProductAttributesByCategory($ids)
    {

        $query = $this->xmlmap->getFilenameID('sql/product', 'getProductAndAttributes');

        $query = $query."
        WHERE product_id IN (".$ids.")
        AND is_delete = 0 AND is_draft = 0
        GROUP BY attr_value
        ORDER BY attr_name
        ";

        $sth = $this->db->conn_id->prepare($query);
        $sth->execute();

        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }


    function getProductConditionByCategory($ids)
    {

        $query = "
        SELECT `condition` from es_product
        WHERE id_product IN (".$ids.")
        AND is_delete = 0 AND is_draft = 0
        GROUP BY `condition`

        ";
        $sth = $this->db->conn_id->prepare($query);
        $sth->execute();

        $rows = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
        return $rows;
    }

    function getProductBrandsByCategory($ids)
    {


        $query = "
        SELECT
            a.`name`
        FROM
            `es_brand` a
            , `es_product` b
        WHERE a.`id_brand` = b.`brand_id`
        AND b.`id_product` IN (".$ids.")
        GROUP BY `name`
        ";

        $sth = $this->db->conn_id->prepare($query);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_COLUMN, 0);

        return $rows;
    }

    // // end of new

    function getBrandsByCategory($cat_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'getBrandsByCategory');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':cat_id',$cat_id);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    function getAvailableBrand($cat_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'getAvailableBrand');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':cat_id',$cat_id);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    /**
     *  Get brand details by brand ID
     *  @param integer $brandID
     *  @return ARRAY()
     */
    public function getBrandById($brandId = 1)
    {
        if(is_array($brandId)){
            if(count($brandId) === 0){
                return false;
            }
            $query =  'SELECT name, id_brand, image, description FROM `es_brand` WHERE id_brand IN ';
            $qmarks = implode(',', array_fill(0, count($brandId), '?'));
            $query = $query.'('.$qmarks.')';
            $sth = $this->db->conn_id->prepare($query);
            $k = 0;
            foreach ($brandId as $id){
                $sth->bindValue(($k+1), $id, PDO::PARAM_INT);
                $k++;
            }
        }else{
            $query = $this->xmlmap->getFilenameID('sql/product', 'getBrandById');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':brand_id',$brandId,PDO::PARAM_INT);
        }

        $sth->execute();
        $brand = $sth->fetchAll(PDO::FETCH_ASSOC);

        return ($sth->rowCount()>0)?$brand:false;
    }


    function selectAttributeNameWithNameAndId($name,$id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'selectAttributeNameWithNameAndId');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':name',$name);
        $sth->bindParam(':id',$id,PDO::PARAM_INT);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    function selectAttributeNameWithTypeAndId($groupID,$datatypeID)
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'selectAttributeNameWithTypeAndId');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id_attr',$groupID, PDO::PARAM_INT);
        $sth->bindParam(':datatype_id',$datatypeID,PDO::PARAM_INT);

        if($sth->execute()){
            $errorInfo = json_encode($sth->errorInfo());
            log_message('error', 'Textare PDO::SELL:SELECT BEFORE ADD => '.$errorInfo);
            log_message('error', 'Textare PDO::QUERY => '.$query);
            log_message('error', 'Textare PDO::VARIABLE(id_attr) => '.$groupID);
            log_message('error', 'Textare PDO::VARIABLE(datatype_id) => '.$datatypeID);
        }

        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }


    function addNewProduct($product_title,$sku,$product_brief,$product_description,$keyword,$brand_id,$cat_id,$style_id,$member_id,$product_price,$product_discount,$product_condition,$other_category_name, $other_brand_name,$search_keyword)
    {

        # this function for adding new product to es_product table.
        $query = $this->xmlmap->getFilenameID('sql/product','addNewProduct_es_product');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':name',$product_title);
        $sth->bindParam(':sku',$sku);
        $sth->bindParam(':brief',$product_brief);
        $sth->bindParam(':description',$product_description);
        $sth->bindParam(':keywords',$keyword);
        $sth->bindParam(':brand_id',$brand_id);
        $sth->bindParam(':cat_id',$cat_id);
        $sth->bindParam(':style_id',$style_id);
        $sth->bindParam(':member_id',$member_id);
        $sth->bindParam(':price',$product_price);
        $sth->bindParam(':discount',$product_discount);
        $sth->bindParam(':condition',$product_condition);
        $sth->bindParam(':cat_other_name',$other_category_name);
        $sth->bindParam(':brand_other_name',$other_brand_name);
        $sth->bindParam(':search_keyword',$search_keyword);
        $now = date('Y-m-d H:i:s');
        $sth->bindParam(':startdate',$now);
        $sth->bindParam(':enddate',$now);
        $sth->bindParam(':createdate',$now);
        $sth->bindParam(':lastmodifieddate',$now);
        $bool = $sth->execute();

        if ((bool)$bool === false) {
            log_message('error', 'Text PDO::SELL:Add => '.json_encode($sth->errorInfo()));
        }
        return $this->db->conn_id->lastInsertId('id_product');
    }

    function addNewAttributeByProduct($product_id,$attribute_id,$value,$price)
    {
        # this function for adding new attribute of the product to es_product_attr table.
        $query = $this->xmlmap->getFilenameID('sql/product','addNewAttribute');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_id',$product_id , PDO::PARAM_INT);
        $sth->bindParam(':attr_id',$attribute_id , PDO::PARAM_INT);
        $sth->bindParam(':attr_value',$value , PDO::PARAM_STR);
        $sth->bindParam(':attr_price',$price , PDO::PARAM_STR);
        if(!$sth->execute()){
            $errorInfo = json_encode($sth->errorInfo());
            log_message('error', 'Textare PDO::SELL:ADD => '.$errorInfo);
            log_message('error', 'Textare PDO::QUERY => '.$query);
            log_message('error', 'Textare PDO::VARIABLE(product_id) => '.$product_id);
            log_message('error', 'Textare PDO::VARIABLE(attr_id) => '.$attribute_id);
            log_message('error', 'Textare PDO::VARIABLE(attr_value) => '.$value);
            log_message('error', 'Textare PDO::VARIABLE(attr_price) => '.$price);
        }

        return $this->db->conn_id->lastInsertId('id_product_attr');
    }

    function addNewAttributeByProduct_others_name($product_id,$name)
    {
        # this function for adding new attribute of the product to es_product_attr table.
        $query = $this->xmlmap->getFilenameID('sql/product','addNewAttributeOtherName');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_id',$product_id);
        $sth->bindParam(':field_name',$name);
        $sth->execute();

        return $this->db->conn_id->lastInsertId('id_product_attr');
    }

    function addNewAttributeByProduct_others_name_value($others_id,$name,$price,$imageid)
    {
        # this function for adding new attribute of the product to es_product_attr table.
        $query = $this->xmlmap->getFilenameID('sql/product','addNewAttributeOtherNameValue');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':head_id',$others_id);
        $sth->bindParam(':value_name',$name);
        $sth->bindParam(':value_price',$price);
        $sth->bindParam(':product_img_id',$imageid);
        $sth->execute();

        return $this->db->conn_id->lastInsertId('id_optional_attrdetail');
    }

    function addNewProductImage($path,$file_type,$product_id,$is_primary)
    {
        # this function for adding new image of the product to es_product_image table.
        $query = $this->xmlmap->getFilenameID('sql/product','addNewProductImage');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_image_path',$path);
        $sth->bindParam(':product_image_type',$file_type);
        $sth->bindParam(':product_id',$product_id);
        $sth->bindParam(':is_primary',$is_primary);
        $sth->execute();

        return $this->db->conn_id->lastInsertId('id_product_image');
    }

    function addNewCombination($product_id,$qty)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','addNewCombination');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_id',$product_id,PDO::PARAM_INT);
        $sth->bindParam(':qty',$qty,PDO::PARAM_INT);
        $sth->execute();
        return $this->db->conn_id->lastInsertId('id_product_item');
    }

    function updateCombination($product_id,$product_item_id,$qty)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','updateCombination');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_id',$product_id,PDO::PARAM_INT);
        $sth->bindParam(':product_item_id',$product_item_id,PDO::PARAM_INT);
        $sth->bindParam(':qty',$qty,PDO::PARAM_INT);
        $sth->execute();
    }

    function updateCombinationAttribute($product_id_item,$product_attr_id,$other_identifier, $product_item_attr_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','updateCombinationAtrribute');
        $sth = $this->db->conn_id->prepare($query);

        $sth->bindParam(':product_id_item',$product_id_item,PDO::PARAM_INT);
        $sth->bindParam(':product_attr_id',$product_attr_id,PDO::PARAM_INT);
        $sth->bindParam(':is_other',$other_identifier,PDO::PARAM_INT);
        $sth->bindParam(':product_item_attr_id',$product_item_attr_id,PDO::PARAM_INT);
        $sth->execute();
    }

    function selectProductItemAttr($product_item_id,$product_attr_id, $is_other){
        $query = $this->xmlmap->getFilenameID('sql/product','selectProductItemAttr');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_item_id',$product_item_id,PDO::PARAM_INT);
        $sth->bindParam(':product_attr_id',$product_attr_id,PDO::PARAM_INT);
        $sth->bindParam(':is_other',$is_other,PDO::PARAM_INT);
        $sth->execute();
        $rows = $sth->fetch(PDO::FETCH_ASSOC);
        return $rows['id_product_item_attr'];
    }

    function selectProductAttribute($attribute_id,$product_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','selectProductAttribute');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':lookupId',$attribute_id,PDO::PARAM_INT);
        $sth->bindParam(':productID',$product_id,PDO::PARAM_INT);
        $sth->execute();
        $rows = $sth->fetch(PDO::FETCH_ASSOC);

        return $rows['id_product_attr'];
    }

    function selectProductAttributeOther($other_group,$other_value,$product_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','selectProductAttributeOther');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':valueGroup',$other_group,PDO::PARAM_STR);
        $sth->bindParam(':valueName',$other_value,PDO::PARAM_STR);
        $sth->bindParam(':productID',$product_id,PDO::PARAM_INT);
        $sth->execute();
        $rows = $sth->fetch(PDO::FETCH_ASSOC);

        return $rows['id_optional_attrdetail'];
    }

    function addNewCombinationAttribute($product_id_item,$product_attr_id,$other_identifier)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','addNewCombinationAtrribute');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_id_item',$product_id_item,PDO::PARAM_INT);
        $sth->bindParam(':product_attr_id',$product_attr_id,PDO::PARAM_INT);
        $sth->bindParam(':is_other',$other_identifier,PDO::PARAM_INT);
        $sth->execute();
        return $this->db->conn_id->lastInsertId('id_product_item_attr');
    }

    function getAttributeByCategoryIdWithDistinct($id)
    {
        # get attribute by selecting category id for filtering item for searcing of the user
        $query = $this->xmlmap->getFilenameID('sql/product','getAttributeByCategoryIdWithDistinct');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':cat_id',$id);
        $sth->execute();

        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    function getAttributeByCategoryIdWithName($id,$name)
    {

        $query = $this->xmlmap->getFilenameID('sql/product','getAttributeByCategoryIdWithName');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':cat_id',$id);
        $sth->bindParam(':name',$name);
        $sth->execute();

        $row = $sth->fetchAll(PDO::FETCH_COLUMN, 0);

        return $row;
    }




    function getProductInCategoryAndUnder($words,$items,$start,$per_page)
    {
        $start = (int)$start;
        $per_page = (int)$per_page;

        $concatQuery = "";

        foreach ($words as $key => $value) {
            $concatQuery .= " AND  ( a.`name` LIKE :like".$key." OR `search_keyword` LIKE :like".$key." )";
        }


        $query = "
        SELECT
            a.`id_product` AS product_id
            , a.`slug` AS product_slug
            , a.`name` AS product_name
            , a.`price` AS product_price
            , a.`brief` AS product_brief
            , a.`condition` AS product_condition
            , b.product_image_path
            , c.`name` AS product_brand
            , a.`brand_id`
        FROM
            `es_product` a
            , `es_product_image` b
            ,`es_brand` c
        WHERE a.`id_product` = b.`product_id`
            AND a.is_delete = 0
            AND a.`is_draft` = 0
            AND b.`is_primary` = 1
            AND a.`brand_id` = c.`id_brand`
            ".$concatQuery."
            AND a.`cat_id` IN (".$items.")

            LIMIT :start, :per_page
        ";

        $sth = $this->db->conn_id->prepare($query);


        foreach ($words as $key => $value) {
            $newValue = '%'.$value.'%';
            $sth->bindParam(':like'.$key,$newValue,PDO::PARAM_STR);
        }

        $sth->bindParam(':start',$start,PDO::PARAM_INT);
        $sth->bindParam(':per_page',$per_page,PDO::PARAM_INT);
        $sth->execute();


        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }


    ///// Advance Search: Updated SQL query - Rain 02/25/14 //////
    function getAttributesWithParam($id,$datas)
    {
        if(is_array($id)){
            $cid = implode(',', $id);
        }else{
            $cid = $id;
        }

        $query = 'SELECT DISTINCT ea.`name` FROM `es_product_attr` epa
            LEFT JOIN `es_attr` ea ON epa.`attr_id` = ea.`id_attr`
            LEFT JOIN `es_product` ep ON epa.`product_id` = ep.`id_product`
            WHERE ep.`cat_id` IN ('. $cid .')
            AND ea.`datatype_id` != 2
            AND epa.`product_id` IN ('. implode(',', $datas) .') ORDER BY ea.`name`';
        $sth = $this->db->conn_id->prepare($query);
        $sth->execute();

        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    } /// end

    ///// Advance Search: Updated SQL query - Rain 02/25/14 //////
    function getAttributesWithParamAndName($id,$datas,$name)
    {
        if(is_array($id)){
            $cid = implode(',', $id);
        }else{
            $cid = $id;
        }

        $query = 'SELECT DISTINCT epa.`attr_value` FROM `es_product_attr` epa
            LEFT JOIN `es_attr` ea ON epa.`attr_id` = ea.`id_attr`
            LEFT JOIN `es_product` ep ON epa.`product_id` = ep.`id_product`
            WHERE ep.cat_id IN ('. $cid .')
            AND ea.`datatype_id` != 2
            AND product_id IN ('. implode(', ', $datas) .')
            AND ea.`name` = :name ORDER BY ea.`name` ';
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':name',$name);
        $sth->execute();

        $row = $sth->fetchAll(PDO::FETCH_COLUMN, 0);

        return $row;
    } /// end

    function addProductReview($memberid, $productid, $rating, $title, $review)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','submitReview');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$memberid);
        $sth->bindParam(':product_id',$productid);
        $sth->bindParam(':rating',$rating);
        $sth->bindParam(':title',$title);
        $sth->bindParam(':review',$review);

        $sth->execute();
    }


    function getProductReview($productid,$last_id = 0){
        if($last_id === 0){
            $query = $this->xmlmap->getFilenameID('sql/product','getRecentProductReviews');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':id',$productid);
            $sth->execute();
            $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        else{
            $query = $this->xmlmap->getFilenameID('sql/product','getMoreProductReviews');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':id',$productid);
            $sth->bindParam(':last_id',$last_id);
            $sth->execute();
            $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        return $row;
    }

    function getReviewReplies($temp, $id){
        $query = $this->xmlmap->getFilenameID('sql/product','getReviewReplies');
        $sth = $this->db->conn_id->prepare($query);
        $size = count($temp);

        while($size<5){
            array_push($temp, $temp[0]);
            $size++;
        }
        array_push ($temp,$id);
        $sth->execute($temp);
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    function getAllowedReviewers($productid)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','getAllowedReviewers');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$productid);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        $row2 = array_map(
            function($x){
                return $x['buyer_id'];
            },
            $row
        );

        return $row2;
    }

    function getVendorRating($uid)
    {
        $query = $this->xmlmap->getFilenameID('sql/users','getUserRating');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$uid);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        $data = array(
            'rate_count' => count($row),
            'rating1' => 0,
            'rating2' => 0,
            'rating3' => 0,
        );

        if($data['rate_count'] > 0)
        {
            foreach($row as $rate)
            {
                $data['rating1'] += $rate['rating1'];
                $data['rating2'] += $rate['rating2'];
                $data['rating3'] += $rate['rating3'];
            }
            $data['rating1'] /= $data['rate_count'];
            $data['rating2'] /= $data['rate_count'];
            $data['rating3'] /= $data['rate_count'];
        }
        return $data;
    }

    function getFirstLevelNode($is_main = false, $is_alpha = false, $is_admin = false) # get all main/parent/first level category from database
    {

        $query = $this->xmlmap->getFilenameID('sql/product', 'selectFirstLevel');
        $protected_categories = array();
        if(!$is_admin){
            $this->config->load('protected_category', TRUE);
            $protected_categories = $this->config->config['protected_category'];
            $qmarks = implode(',', array_fill(0, count($protected_categories), '?'));
            $query = $query.' AND c.id_cat NOT IN ('.$qmarks.') ';
        }

        $query .= ' AND c.id_cat != 1';

        if(($is_main)&&(!$is_alpha)){
            $query = $query.' AND c.is_main = 1 ORDER BY c.sort_order ASC, c.id_cat ASC';
        }
        else if((!$is_main)&&(!$is_alpha)){
            $query = $query.' ORDER BY c.sort_order ASC,c.id_cat ASC';
        }
        else if(($is_main)&&($is_alpha)){
            $query = $query.' AND c.is_main = 1 ORDER BY c.name ASC,c.id_cat ASC';
        }
        else if((!$is_main)&&($is_alpha)){
            $query = $query.' ORDER BY c.name ASC,c.id_cat ASC';
        }

        $sth = $this->db->conn_id->prepare($query);

        $k = 0;
        foreach ($protected_categories as $id){
            $sth->bindValue(($k+1), $id, PDO::PARAM_INT);
            $k++;
        }

        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }



    function addReply($data = array())
    {
        $query = $this->xmlmap->getFilenameID('sql/product','addReply');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':review',$data['review']);
        $sth->bindParam(':p_reviewid',$data['p_reviewid']);
        $sth->bindParam(':product_id',$data['product_id']);
        $sth->bindParam('member_id',$data['member_id']);
        $sth->execute();

    }

    function updateIsDelete($productid, $memberid,$is_delete){
        $query = $this->xmlmap->getFilenameID('sql/product', 'updateIsDelete');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':productid',$productid,PDO::PARAM_INT);
        $sth->bindParam(':memberid',$memberid,PDO::PARAM_INT);
        $sth->bindParam(':is_delete', $is_delete,PDO::PARAM_INT);
        $sth->bindParam(':datenow', date('Y-m-d H:i:s'));

        $sth->execute();
    }

    function createSlug($title, $table = 0)
    {
        $titleLower = strtolower($title);
        $slugGenerate = "";
        if($table == 0){
            $query = "SELECT COUNT(slug) AS cnt_slug FROM `es_product` WHERE slug LIKE :slug ";
        }else if($table == 1){
            $query = "SELECT COUNT(slug) AS cnt_slug FROM `es_cat` WHERE slug LIKE :slug ";
        }
        $bindValue = es_url_clean($titleLower).'%';
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':slug',$bindValue ,PDO::PARAM_STR);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_COLUMN,0);
        $cnt = $row[0];

        if($cnt > 0){
            $slugGenerate = es_url_clean($titleLower.'-'.$cnt++);
        }else{
            $slugGenerate = es_url_clean($titleLower);
        }

        #IDEALLY, THIS BLOCK OF CODE IS NOT NEEDED, HOWEVER THERE ARE RARE INSTANCES WHEN
        #THE SAME SLUG IS GENERATED FOR TWO ENTRIES
        if($table == 0){
            $query = "SELECT id_product as id FROM es_product WHERE slug = :slug";
        }
        else if($table == 1){
            $query = "SELECT id_cat as id FROM es_cat WHERE slug = :slug";
        }
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':slug',$slugGenerate ,PDO::PARAM_STR);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        if($row['id']>0){
            $slugGenerate = $slugGenerate.'-'.$row['id'];
        }
        return $slugGenerate;
    }

    function checkifexistcategory($cat_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'checkifexistcategory');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':cat_id',$cat_id,PDO::PARAM_STR);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_COLUMN,0);
        return $row[0];
    }

    function getPopularitem($cat_ids,$limit)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','getPopularitem');

        $qmarks = implode(',', array_fill(0, count($cat_ids), '?'));
        $query = $query.'('.$qmarks.') ORDER BY `clickcount` DESC LIMIT ?';

        $sth = $this->db->conn_id->prepare($query);
        array_push($cat_ids, intval($limit));
        foreach ($cat_ids as $k => $id)
        $sth->bindValue(($k+1), $id, PDO::PARAM_INT);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        explodeImagePath($rows);

        return $rows;
    }


    function getRecommendeditem($cat_id,$limit,$prod_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','getPopularitem');
        $query = $query.'(?) ORDER BY `clickcount` DESC LIMIT ?';
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(1,$cat_id, PDO::PARAM_INT);
        $sth->bindParam(2,$limit, PDO::PARAM_INT);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $key=>$row){
            if(intval($row['id_product']) == intval($prod_id)){
                unset($rows[$key]);
            }
        }

        foreach($rows as $idx => $row){
            applyPriceDiscount($row);
            $rows[$idx] = $row;
        }

        explodeImagePath($rows);

        return array_values($rows);
    }

    function getProductById($id, $extended = false)
    {

        if($extended){
            $query = $this->xmlmap->getFilenameID('sql/product', 'getProductByIdExtended');
        }
        else{
            $query = $this->xmlmap->getFilenameID('sql/product','getProductById');
        }

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id);
        $sth->execute();
        $product = $sth->fetch(PDO::FETCH_ASSOC);

        /* Get actual price, apply any promo calculation */
        applyPriceDiscount($product);
        /* Separate image file path and file name */
        $temp = array($product);
        explodeImagePath($temp);
        $product = $temp[0];
        if(isset($product['userpic']) && strlen(trim($product['userpic']))===0){
            $product['userpic'] = 'assets/user/default';
        }

        return $product;
    }

    function getProductCount($down_cat)
    {
        $qmarks = implode(',', array_fill(0, count($down_cat), '?'));
        $query = $this->xmlmap->getFilenameID('sql/product','getProductCount');
        $query = $query.'('.$qmarks.')';
        $sth = $this->db->conn_id->prepare($query);
        $sth->execute($down_cat);
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    function getProductEdit($product_id, $member_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','getProductEdit');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_id',$product_id);
        $sth->bindParam(':member_id',$member_id);
        $sth->execute();

        $row = $sth->fetch(PDO::FETCH_ASSOC);
        if(intval($row['brand_id'],10) === 1){
            $row['brandname'] = ($row['brand_other_name'] !== '')?$row['brand_other_name']:'Custom brand';
        }

        return $row;
    }

    public function editProduct($product_details=array(),$member_id)
    {
        $is_sold_out = '0';
        $is_sold_out = '0';
        $query = $this->xmlmap->getFilenameID('sql/product','editProduct');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':name',$product_details['name']);
        $sth->bindParam(':sku',$product_details['sku']);
        $sth->bindParam(':brief',$product_details['brief']);
        $sth->bindParam(':description',$product_details['description']);
        $sth->bindParam(':keywords',$product_details['keyword']);
        $sth->bindParam(':brand_id',$product_details['brand_id']);
        $sth->bindParam(':style_id',$product_details['style_id']);
        $sth->bindParam(':cat_id',$product_details['cat_id']);
        $sth->bindParam(':price',$product_details['price']);
        $sth->bindParam(':condition',$product_details['condition']);
        $sth->bindParam(':p_id',$product_details['product_id']);
        $sth->bindParam(':member_id',$member_id);
        $sth->bindParam(':brand_other_name',$product_details['brand_other_name']);
        $sth->bindParam(':cat_other_name',$product_details['cat_other_name']);
        $sth->bindParam(':modifieddate', date('Y-m-d H:i:s'));
        $sth->bindParam(':is_sold_out', $is_sold_out);
        $sth->bindParam(':discount', $product_details['discount']);
        $sth->bindParam(':search_keyword', $product_details['search_keyword']);

        $bool = $sth->execute();

        return $sth->rowCount();
    }

    function removeProductDetails($productId)
    {
        $query1 = $this->xmlmap->getFilenameID('sql/product','removeProductImage');
        $sth1 = $this->db->conn_id->prepare($query1);
        $sth1->bindParam(':productId',$productId);
        $sth1->execute();

        $query2 = $this->xmlmap->getFilenameID('sql/product','removeProductOptionalAttributeDetails');
        $sth2 = $this->db->conn_id->prepare($query2);
        $sth2->bindParam(':productId',$productId);
        $sth2->execute();

        $query3 = $this->xmlmap->getFilenameID('sql/product','removeProductOptionalAttributeHead');
        $sth3 = $this->db->conn_id->prepare($query3);
        $sth3->bindParam(':productId',$productId);
        $sth3->execute();

        $query4 = $this->xmlmap->getFilenameID('sql/product','removeProductItemAttr');
        $sth4 = $this->db->conn_id->prepare($query4);
        $sth4->bindParam(':productId',$productId);
        $sth4->execute();

        $query5 = $this->xmlmap->getFilenameID('sql/product','removeProductItem');
        $sth5 = $this->db->conn_id->prepare($query5);
        $sth5->bindParam(':productId',$productId);
        $sth5->execute();

        $query6 = $this->xmlmap->getFilenameID('sql/product','removeProductAttr');
        $sth6 = $this->db->conn_id->prepare($query6);
        $sth6->bindParam(':productId',$productId);
        $sth6->execute();
    }

    function getShippingDetailsByItemId($itemId)
    {
        $query1 = $this->xmlmap->getFilenameID('sql/product','getShippingDetailsByItemId');
        $sth1 = $this->db->conn_id->prepare($query1);
        $sth1->bindParam(':itemId',$itemId);
        $sth1->execute();
        $row = $sth1->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    function editProductCategory($cat_id,$product_id,$member_id, $other_cat_name = '')
    {

        $query = $this->xmlmap->getFilenameID('sql/product','editProductCategory');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$product_id);
        $sth->bindParam(':member_id',$member_id);
        $sth->bindParam(':cat_id',$cat_id);
        $sth->execute();
        $bool_update_count = $sth->rowCount();
        if($other_cat_name !== ''){
            $query = 'UPDATE es_product SET cat_other_name = :other_cat_name WHERE id_product = :id AND member_id = :member_id';
            $xth = $this->db->conn_id->prepare($query);
            $xth->bindParam(':id',$product_id);
            $xth->bindParam(':member_id',$member_id);
            $xth->bindParam(':other_cat_name',$other_cat_name);
            $xth->execute();
        }

        return $bool_update_count;
    }



    #Deletes product attributes in es_product_attr table, returns number of affected rows
    function deleteAttributeByProduct($product_id,$attribute_id=0)
    {
        if($attribute_id !== 0){
            $query = $this->xmlmap->getFilenameID('sql/product','deleteProductAttributeByIDs');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':product_id',$product_id);
            $sth->bindParam(':attr_id',$attribute_id);
        }
        else{
            $query = $this->xmlmap->getFilenameID('sql/product','deleteProductAttributes');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':product_id',$product_id);
        }
        $sth->execute();

        return $sth->rowCount();
    }

    #Deletes product image in es_product_image table, returns number of affected rows
    function deleteProductImage($product_id, $image_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','deleteProductImage');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_id',$product_id);
        $sth->bindParam(':image_id',$image_id);
        $sth->execute();

        return $sth->rowCount();
    }

    function deleteAttrOthers($other_head_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','deleteOtherDetail');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':head_id',$other_head_id);
        $sth->execute();

        $query = $this->xmlmap->getFilenameID('sql/product','deleteOtherHead');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':head_id',$other_head_id);
        $sth->execute();
    }


    function updateImageIsPrimary($image_id, $is_primary){
        $query = $this->xmlmap->getFilenameID('sql/product','updateImageIsPrimary');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':image_id',$image_id, PDO::PARAM_INT);
        $sth->bindParam(':is_primary',$is_primary, PDO::PARAM_INT);

        $sth->execute();
    }

    public function getProductQuantity($product_id, $verbose = false, $check_lock = false, $start_promo = false){

        /*
        *  Check if their is a quantity limit enforced by promo
        */

        $promo_quantity_limit = PHP_INT_MAX;
        if($start_promo){
            $query = "SELECT promo_type, is_promote, startdate, enddate FROM es_product WHERE id_product = :product_id";
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':product_id',$product_id, PDO::PARAM_INT);
            $sth->execute();
            $product_promo = $sth->fetch(PDO::FETCH_ASSOC);
            if(intval($product_promo['is_promote']) === 1){
                $promo_option =  $this->config->item('Promo')[$product_promo['promo_type']]['option'];
                $His = strtotime(date('H:i:s'));

                $start_datetime = $product_promo['startdate'];
                $end_datetime = $product_promo['enddate'];

                foreach($promo_option as $opt ){
                    if((strtotime($opt['start']) <= $His) && (strtotime($opt['end']) > $His)){
                        $promo_quantity_limit = $opt['purchase_limit'];
                        $start_datetime = date('Y-m-d',strtotime($start_datetime)).' '.$opt['start'];
                        $end_datetime = date('Y-m-d',strtotime($end_datetime)).' '.$opt['end'];
                        break;
                    }
                }
                if(isset($opt['puchase_limit'])){
                    $query = "SELECT COALESCE(SUM(op.order_quantity),0) as sold_count FROM es_order_product op
                            INNER JOIN es_order o ON o.id_order = op.order_id AND o.dateadded between :start AND :end
                            AND o.order_status != 99 AND o.order_status != 2
                            WHERE product_id = :product_id";

                    $sth = $this->db->conn_id->prepare($query);
                    $sth->bindParam(':product_id',$product_id, PDO::PARAM_INT);
                    $sth->bindParam(':start',$start_datetime, PDO::PARAM_STR);
                    $sth->bindParam(':end',$end_datetime, PDO::PARAM_STR);
                    $sth->execute();
                    $sold_count = $sth->fetch(PDO::FETCH_ASSOC)['sold_count'];

                    $promo_quantity_limit = $opt['purchase_limit'] - $sold_count;
                    $promo_quantity_limit = ($promo_quantity_limit >= 0)?$promo_quantity_limit:0;
                }


            }
        }


        if($verbose){
            $query = $this->xmlmap->getFilenameID('sql/product','getProductQuantityVerbose');
        }
        else{
            $query = $this->xmlmap->getFilenameID('sql/product','getProductQuantity');
        }
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_id',$product_id, PDO::PARAM_INT);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

        $data = array();
        foreach($rows as $row){
            if(!array_key_exists($row['id_product_item'],  $data)){
                $data[$row['id_product_item']] = array();
                $data[$row['id_product_item']]['quantity'] = ($row['quantity'] <= $promo_quantity_limit)?$row['quantity']:$promo_quantity_limit;
                $data[$row['id_product_item']]['product_attribute_ids'] = array();
                $data[$row['id_product_item']]['attr_lookuplist_item_id'] = array();
                $data[$row['id_product_item']]['attr_name'] = array();
            }
            array_push($data[$row['id_product_item']]['product_attribute_ids'], array('id'=>$row['product_attr_id'], 'is_other'=> $row['is_other']));
            if($verbose){
                array_push($data[$row['id_product_item']]['attr_lookuplist_item_id'], $row['attr_lookuplist_item_id']);
                array_push($data[$row['id_product_item']]['attr_name'], $row['attr_value']);
            }
        }

        // In cases where the previous query (specifically 'getProductQuantityVerbose') does not return any
        // result due to the INNER JOIN with es_product_item_attr (which happens with the default qty), we
        // query using the non-verbose version to get the default quantity result set.
        if(($verbose)&&(count($data) === 0)){
            $query = $this->xmlmap->getFilenameID('sql/product','getProductQuantity');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':product_id',$product_id, PDO::PARAM_INT);
            $sth->execute();
            $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
            if(count($rows) === 1){
                if((intval($rows[0]['product_attr_id'],10) === 0) && (intval($rows[0]['is_other'],10) === 0)){
                    $data[$rows[0]['id_product_item']] = array();
                    $data[$rows[0]['id_product_item']]['quantity'] = $rows[0]['quantity'];
                    $data[$rows[0]['id_product_item']]['product_attribute_ids'] =  array(0=>array('id'=>$rows[0]['product_attr_id'], 'is_other'=> $rows[0]['is_other']));
                    $data[$rows[0]['id_product_item']]['attr_lookuplist_item_id'] = array();
                    $data[$rows[0]['id_product_item']]['attr_name'] = array();
                }
            }
        }


        $query = 'SELECT lck.id_item_lock, pi.id_product_item, lck.qty as lock_qty, lck.timestamp, pi.quantity 
        FROM es_product_item_lock lck INNER JOIN es_product_item pi ON pi.product_id = :product_id AND lck.product_item_id = pi.id_product_item';
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_id',$product_id, PDO::PARAM_INT);
        $sth->execute();
        $lockdata = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach($lockdata as $lock){
            //DELETE LOCK ITEMS THAT ARE 5 MINUTES IN LIFE TIME
            if(round((strtotime("now") - strtotime($lock['timestamp']))/60) > 10){
                $this->deleteProductItemLock($lock['id_item_lock']);
            }else{
                if(isset($data[$lock['id_product_item']]) && $check_lock){
                    $data[$lock['id_product_item']]['quantity'] -=  $lock['lock_qty'];
                    $data[$lock['id_product_item']]['quantity'] = ($data[$lock['id_product_item']]['quantity'] >= 0)?$data[$lock['id_product_item']]['quantity']:0;
                }
            }
        }

        return $data;
    }


    public function deleteProductItemLock($item_lock_id){
        $query = 'DELETE FROM es_product_item_lock WHERE id_item_lock = :id';
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$item_lock_id, PDO::PARAM_INT);
        $sth->execute();
    }

    public function deleteShippingInfomation($product_id, $keep_product_item_id = array()){
        $query = "SELECT id_shipping FROM es_product_shipping_head WHERE product_id = :product_id";
        $sth = $this->db->conn_id->prepare($query);
    $sth->bindParam(':product_id',$product_id, PDO::PARAM_INT);
    $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        if(count($rows) > 0){
            $query = "DELETE FROM es_product_shipping_detail WHERE shipping_id IN ";
            $qmarks = implode(',', array_fill(0, count($rows), '?'));
            $query = $query.'('.$qmarks.')';

            if(count($keep_product_item_id) != 0){
                $query = $query." AND product_item_id NOT IN ";
                $qmarks = implode(',', array_fill(0, count($keep_product_item_id), '?'));
                $query = $query.'('.$qmarks.')';
            }

            $xth = $this->db->conn_id->prepare($query);
            $cnt = 0;
            foreach ($rows as $k => $id){
                $xth->bindValue(($k+1), $id['id_shipping'], PDO::PARAM_INT);
                $cnt++;
            }
            foreach ($keep_product_item_id as $k => $id){
                $xth->bindValue(($cnt+$k+1), $id, PDO::PARAM_INT);
            }
            $xth->execute();

            $query = "SELECT shipping_id FROM es_product_shipping_detail WHERE shipping_id IN ";
            $qmarks = implode(',', array_fill(0, count($rows), '?'));
            $query = $query.'('.$qmarks.')';
            $xth = $this->db->conn_id->prepare($query);
            foreach ($rows as $k => $id){
                $xth->bindValue(($k+1), $id['id_shipping'], PDO::PARAM_INT);
            }
            $xth->execute();
            $retain_shipping_id = $xth->fetchAll(PDO::FETCH_ASSOC);

            if(count($retain_shipping_id) > 0){
                $query = "DELETE FROM es_product_shipping_head WHERE id_shipping NOT IN ";
                $qmarks = implode(',', array_fill(0, count($retain_shipping_id), '?'));
                $query = $query.'('.$qmarks.')';
                $query = $query.' AND product_id = ?';
                $xth = $this->db->conn_id->prepare($query);
                $cnt = 0;
                foreach ($retain_shipping_id as $k => $id){
                    $xth->bindValue(($k+1), $id['shipping_id'], PDO::PARAM_INT);
                    $cnt++;
                }
                $xth->bindValue(($cnt+1), $product_id, PDO::PARAM_INT);
                $xth->execute();
            }
            else{
                $query = "DELETE FROM es_product_shipping_head WHERE product_id = :product_id";
                $xth = $this->db->conn_id->prepare($query);
                $xth->bindParam(':product_id',$product_id, PDO::PARAM_INT);
                $xth->execute();
            }
        }
    }


    public function deleteProductQuantityCombination($product_id, $keep_product_item_id=array()){

        if(count($keep_product_item_id) == 0){
            $query = "SELECT id_product_item FROM es_product_item WHERE product_id = ?";
        }
        else{
            $query = "SELECT id_product_item FROM es_product_item WHERE id_product_item NOT IN ";
            $qmarks = implode(',', array_fill(0, count($keep_product_item_id), '?'));
            $query = $query.'('.$qmarks.')';
            $query = $query.' AND product_id = ?';
        }

        $sth = $this->db->conn_id->prepare($query);
        $cnt = 0;
        foreach ($keep_product_item_id as $k => $id){
            $sth->bindValue(($k+1), $id, PDO::PARAM_INT);
            $cnt++;
        }
        $sth->bindValue(($cnt+1), $product_id, PDO::PARAM_INT);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

        if(count($rows) > 0){
            $query = "DELETE FROM es_product_item_attr WHERE product_id_item IN ";
            $qmarks = implode(',', array_fill(0, count($rows), '?'));
            $query = $query.'('.$qmarks.')';
            $xth = $this->db->conn_id->prepare($query);
            foreach ($rows as $k => $id){
                $xth->bindValue(($k+1), $id['id_product_item'], PDO::PARAM_INT);
            }
            $xth->execute();

            $query = "DELETE FROM es_product_item WHERE id_product_item IN ";
            $qmarks = implode(',', array_fill(0, count($rows), '?'));
            $query = $query.'('.$qmarks.')';
            $query = $query.' AND product_id = ?';
            $xth = $this->db->conn_id->prepare($query);
            $cnt = 0;
            foreach ($rows as $k => $id){
                $xth->bindValue(($k+1), $id['id_product_item'], PDO::PARAM_INT);
                $cnt++;
            }
            $xth->bindValue(($cnt+1), $product_id, PDO::PARAM_INT);
            $xth->execute();
        }
    }

    public function getCategoriesNavigation(){
        $query = $this->xmlmap->getFilenameID('sql/product','getCategoriesNavigation');
        $sth = $this->db->conn_id->prepare($query);
        $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public function getParentCategory(){
        $query = "SELECT id_cat, name FROM es_cat WHERE parent_id = 1 AND id_cat > 1 ";
        $sth = $this->db->conn_id->prepare($query);
        $sth->execute();
        $rows = $sth->fetchAll();
        return $rows;
    }


    /********************************************************************************/
    /***********************    STEP 3 PRODUCT UPLOAD   *****************************/
    /********************************************************************************/

    /**
    *   Fetch Locations from location lookup table to fill dropdown listbox
    */
    public function getLocation()
    {
        $query = $this->xmlmap->getFilenameID('sql/product','getLocation');
        $sth = $this->db->conn_id->prepare($query);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        $data = array();

        foreach($row as $r){
            $data['area'][$r['location']][$r['region']][$r['id_cityprov']] = $r['cityprov'];
            $data['islandkey'][$r['location']] = $r['id_location'];
            $data['regionkey'][$r['region']] = $r['id_region'];
        }

        return $data;
    }

    /**
        *   Fetch Product Attr Combinations based on product ID and from Product Upload Step 2
        */
    public function getPrdShippingAttr($prd_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','getPrdShippingAttr');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(":id", $prd_id);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        $data = array();


        if(count($row) === 1 && $row[0]['product_id_item'] == ''){
            $data['has_attr'] = 0;
            $data['product_item_id'] = $row[0]['id_product_item'];
            $data['attributes'][$row[0]['id_product_item']] = array();
        }
        else{
            foreach($row as $r){
                if($r['product_id_item'] != '' && $r['attr_value'] != ''){
                    $data['attributes'][$r['product_id_item']][] = array(
                        'name' => $r['name'],
                        'value' => $r['attr_value']
                    );
                }
            }
            $data['has_attr'] = 1;
        }

        return $data;
    }

    /**
    *   Store Shipping Price in `es_product_shipping_head`
    *   Table contains -> Location ID vs Price
    */
    public function storeShippingPrice ($locationKey, $price, $productId)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','storeShippingPrice');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':location_id', $locationKey, PDO::PARAM_INT);
        $sth->bindParam(':price', $price, PDO::PARAM_INT);
        $sth->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $sth->execute();

        return $this->db->conn_id->lastInsertId('id_shipping');
    }

    /**
        *   Store Product Shipping Mapping in `es_product_shipping_details`
        *   Table contains -> Mapping of ShippingID vs ProductItemAttrID
        */
    public function storeProductShippingMap($shippingId, $attrCombinationId)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','storeProductShippingMap');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':shipping_id', $shippingId, PDO::PARAM_INT);
        $sth->bindParam(':product_item_id', $attrCombinationId, PDO::PARAM_INT);
        $result = $sth->execute();

        return $result;
    }

    /**
        *   Store shipping preference head
        */
    public function storeShippingPreferenceHead($member_id, $title)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','storeShippingPreferenceHead');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':title', $title, PDO::PARAM_STR);
        $sth->bindParam(':member_id', $member_id, PDO::PARAM_INT);
        $result = $sth->execute();

        return $result ? $this->db->conn_id->lastInsertId('id_shipping_pref_head') : $result;
    }

    /**
        *   Store shipping preference detail
        */
    public function storeShippingPreferenceDetail($headId, $locId, $price)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','storeShippingPreferenceDetail');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':loc', $locId, PDO::PARAM_INT);
        $sth->bindParam(':price', $price, PDO::PARAM_STR);
        $sth->bindParam(':head_id', $headId, PDO::PARAM_INT);
        $result = $sth->execute();

        return $result;
    }

    /*
     *  Check if shipping preference ID sent matches current user
     */
    public function getShippingPreferenceHead($headId, $member_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','getShippingPreferenceHead');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $member_id, PDO::PARAM_INT);
        $sth->bindParam(':head_id', $headId, PDO::PARAM_INT);
        $result = $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    /*
     * Delete BOTH shipping preference head and detail in one query
     */
    public function deleteShippingPreference($headId, $member_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','deleteShippingPreference');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $member_id, PDO::PARAM_INT);
        $sth->bindParam(':head_id', $headId, PDO::PARAM_INT);
        $result = $sth->execute();

        return $result;
    }

    public function getProductItem($productId, $memberId)
    {
        $query = $this->xmlmap->getFilenameID('sql/product','getProductItem');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $sth->bindParam(':member_id', $memberId, PDO::PARAM_INT);
        $result = $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }

    public function getShippingSummary($prod_id)
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'getShippingSummary');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':prod_id', $prod_id, PDO::PARAM_INT);
        $result = $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        $data = array(
            'has_shippingsummary' => false,
            'is_freeshipping' => false,
            'location_lookup' => array(),
            'shipping_locations' => array(), # json_encode to use for checkdata
            'shipping_display' => array(
                ''=>array(
                    'location' => array(
                            '' => array(
                                ''=>''
                            )
                        ),
                    'attr' => array(''=>''),
                    'disable_lookup' => array()
                )
            ) # array for displaying set location and price
        );

        $locationPriceArray = array();

        $deliveryCount = $summaryCount = $freeCount = 0;

        foreach($row as $r){
            $pid = (int)$r['id_product_item'];
            $locid = (int)$r['id_location'];
            $price = (int)$r['price'];

            #assemble json data
            if( !isset($data['shipping_locations'][$pid]) ){
                $data['shipping_locations'][$pid] = array();
            }
            $data['location_lookup'][$r['id_location']] = $r['location'];

            if( $locid === 0 && $price === 0 ){ #no shipping detail provided
                $deliveryCount++;
            }else if( $locid === 1 && $price === 0 ){ #Philippines with 0 shipping fee - FREE SHIPPING
                $freeCount++;
            }else{ #Shipping detail provided - HAS SHIPPING SUMMARY
                $summaryCount++;
            }
        }

        $data['is_delivery'] = count($row) === $deliveryCount ? false:true;

        if( $data['is_delivery'] ){
            if( $freeCount > $summaryCount ){
                $data['is_freeshipping'] = true;
            }else if( $summaryCount > $freeCount ){
                $data['has_shippingsummary'] = true;
            }

            if( $data['has_shippingsummary'] ){
                #assemble all necessary arrays to build final array
                foreach( $row as $r ){
                    $pid = (int)$r["id_product_item"];
                    $loc = (int)$r["id_location"];
                    $price = number_format($r["price"], 2, '.', ',');

                    if( $loc !== 0 && $loc !== 1 ){
                        #Push location for product item id
                        if( !in_array($loc, $data['shipping_locations'][$pid]) ){
                            $data['shipping_locations'][$pid][] = $loc;
                        }
                        #Create array with location vs price
                        if( !isset( $locationPriceArray[$pid][$loc] ) ){
                            $locationPriceArray[$pid][$loc] = $price;
                        }
                    }
                }

                #assemble display array
                $arr1 = $locationPriceArray;
                $arr2 = $locationPriceArray; #array being reduced

                $finalarr = array();

                foreach( $arr1 as $attr1=>$t1 ){
                    do{
                        $isFound = false;
                        $minIntersectCount = 0;
                        $intersectArray = array();

                        # Get attr combination with least intersection
                        foreach( $arr2 as $attr2=>$t2 ){
                            if( $attr1 === $attr2 ){
                                continue;
                            }else{
                                #get array intersection comparing keys and values
                                $temp1 = array_intersect_assoc($t1, $t2);
                                #if array intersection is not empty
                                if( count($temp1) > 0 ){
                                    $isFound = true;
                                    if( $minIntersectCount === 0 || count($temp1) < $minIntersectCount ){
                                        $minIntersectCount = count($temp1);
                                        $intersectArray['location'] = $temp1;
                                    }
                                }
                            }
                        }
                        if($isFound){
                            $intersectArray['attr'] = array();
                            # Check again arr2 for values with those of intersectArray[location]
                            foreach( $arr2 as $attr2=>$t2 ){
                                $arrIntersectDiff = array_diff_assoc($intersectArray['location'],$t2);
                                if( count($arrIntersectDiff)===0 ){
                                    $intersectArray['attr'][] = $attr2;
                                }
                            }
                            $isExist = $hasDuplicate = false;
                            #Check finalarr if intersect array set is already contained
                            foreach( $finalarr as $fkey=>$farr ){
                                $sizeOfIntersect = count($intersectArray['location']);
                                $sizeOfLocGroup = count($farr['location']);

                                if( $sizeOfLocGroup > $sizeOfIntersect ){
                                    $sizeOfDiff = count(array_diff_assoc($farr['location'],$intersectArray['location']));
                                    $sizeOfLarger = $sizeOfLocGroup;
                                }else{
                                    $sizeOfDiff = count(array_diff_assoc($intersectArray['location'],$farr['location']));
                                    $sizeOfLarger = $sizeOfIntersect;
                                }

                                #If array set extracted already exists, push new attributes to attribute array
                                if( $sizeOfDiff === 0 && $sizeOfLocGroup === $sizeOfIntersect ){
                                    #if attr1 not in attr array, then push
                                    if( !in_array($attr1, $farr['attr']) ){
                                        $finalarr[$fkey]['attr'][] = $attr1;
                                    }
                                    #push attr2 not in final array
                                    $attrDiff = array_diff($intersectArray['attr'],$farr['attr']);
                                    if( count($attrDiff) > 0 ){
                                        $finalarr[$fkey]['attr'] = array_merge($farr['attr'], $intersectArray['attr']);
                                    }
                                    $isExist = true;
                                    break; #exit foreach since set has been found
                                # If difference is not equal to 0 and not equal to intersect array, hence location was used
                                }else if( $sizeOfDiff !== 0 && $sizeOfDiff !== $sizeOfLarger ){
                                    $hasDuplicate = true;
                                    break; #exit foreach since duplicate location exists in intersect array
                                }
                            }
                            # if intersect array does not exist in final arr and has no duplicate location, push
                            if(!$isExist && !$hasDuplicate){
                                $finalarr[] = $intersectArray;
                            }
                            # Reduce $t1 - intersect array being compared and $arr2 - array checked for intersection
                            $t1 = array_diff_assoc($t1,$intersectArray['location']);
                            foreach( $intersectArray['attr'] as $ik ){
                                $arr2[$ik] = array_diff_assoc($arr2[$ik],$intersectArray['location']);
                            }
                        }
                    }while($isFound && count($t1)>0 );
                }

                #Push all remaining attribute location price with no pair from $arr2 into $finalarr
                foreach($arr2 as $attrk=>$locpricearr){
                    if(count($locpricearr) > 0){
                        $finalarr[] = array(
                            'location' => $locpricearr,
                            'attr' => array($attrk)
                        );
                    }
                }

                #Group location by same price
                foreach( $finalarr as $fkey=>$farr ){
                    $locPriceFilter = array();
                    $disablearr = array();
                    foreach($farr['location'] as $locid=>$price){
                        if( !isset($locPriceFilter[$price]) ){
                            $locPriceFilter[$price] = array();
                        }
                        if( !in_array($locid, $locPriceFilter[$price]) ){
                            $locPriceFilter[$price][] = $locid;
                        }
                        # Push each location once to disable array for disabling purposes on php load of view
                        if( !in_array($locid,$disablearr) ){
                            $disablearr[] = $locid;
                        }
                    }
                    $finalarr[$fkey]['location'] = $locPriceFilter;
                    $finalarr[$fkey]['disable_lookup'] = $disablearr;
                }
                $data['shipping_display'] = $finalarr;
            }
        }

        if( $data['has_shippingsummary'] && !$data['is_freeshipping']){
            $data['str_deliverycost'] = "details";
        }else if( $data['is_freeshipping'] && !$data['has_shippingsummary'] ){
            $data['str_deliverycost'] = "free";
        }else{
            $data['str_deliverycost'] = "off";
        }

        return $data;
    }

    public function getShippingPreference($member_id)
    {
        // Get shipping_id from es_product_shipping_detail before delete
        $query = $this->xmlmap->getFilenameID('sql/product', 'getShippingPreference');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $member_id);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        $data = array();

        foreach($row as $r){
            $r['price'] = number_format($r['price'], 2, '.', ',');

            if( !isset( $data['name'][$r['head_id']] ) ){
                $data['name'][$r['head_id']] = $r['title'];
            }
            $data[$r['head_id']][$r['price']][] = (int)$r['location_id'];
        }

        return $data;
    }

    public function deleteShippingSummaryOnEdit($arrProductItemId)
    {
        // Get shipping_id from es_product_shipping_detail before delete
        $query = $this->xmlmap->getFilenameID('sql/product', 'getShippingIdFromShippingDetail');
        for( $i=0; $i<count($arrProductItemId); $i++ ){
            $query .= '?,';
        }
        $query = substr($query, 0, -1);
        $query .= ' )';
        $sth = $this->db->conn_id->prepare($query);
        $sth->execute($arrProductItemId);
        $tempShippingId = $sth->fetchAll(PDO::FETCH_ASSOC);
        $arrShippingId = array();

        foreach($tempShippingId as $sid){
            $arrShippingId[] = $sid['shipping_id'];
        }

        // Delete Shipping Detail Entries
        $query = $this->xmlmap->getFilenameID('sql/product', 'deleteShippingDetail');
        for( $i=0; $i<count($arrProductItemId); $i++ ){
            $query .= '?,';
        }
        $query = substr($query, 0, -1);
        $query .= ' )';
        $sth = $this->db->conn_id->prepare($query);
            $sth->execute($arrProductItemId);

        // Delete Shipping Head Entries
        $query = $this->xmlmap->getFilenameID('sql/product', 'deleteShippingHead');
        for( $i=0; $i<count($arrShippingId); $i++ ){
            $query .= '?,';
        }
        $query = substr($query, 0, -1);
        $query .= ' )';
        $sth = $this->db->conn_id->prepare($query);
            $sth->execute($arrShippingId);
    }

    /*
        * Return All Draft items by the user.
        * @member_id
        */
    public function getDraftItems($member_id){
        $query = $this->xmlmap->getFilenameID('sql/product','getDraftItems');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $member_id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function deleteDraft($member_id,$product_id){
        $query = $this->xmlmap->getFilenameID('sql/product','deleteDraft');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $member_id, PDO::PARAM_INT);
        $sth->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $return = $sth->execute();
        $retvalue = 0;
        if($return){
            $retvalue = 1;
        }
        return $retvalue;
    }

    public function getShipmentInformation($product_id){
        $query = $this->xmlmap->getFilenameID('sql/product','getShipmentInformation');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':prod_id', $product_id, PDO::PARAM_INT);
        $result = $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

        $data = array();
        foreach($rows as $row){
            if(!array_key_exists($row['id_shipping'], $data)){
                $data[$row['id_shipping']] = array();
                $data[$row['id_shipping']]['location'] = $row['location'];
                $data[$row['id_shipping']]['price'] = $row['price'];
                $data[$row['id_shipping']]['location_type'] = $row['type'];
                $data[$row['id_shipping']]['location_id'] = $row['id_location'];
                $data[$row['id_shipping']]['product_item_id'] = $row['product_item_id'];
                $data[$row['id_shipping']]['product_attribute_ids'] = array();
            }
            array_push($data[$row['id_shipping']]['product_attribute_ids'], array('id' => $row['product_attr_id'], 'is_other' => $row['is_other']));
        }
        return $data;
    }

    /**
     * Get category details based on given slug
     * @param  string $slug
     * @return mixed
     */
    public function getCategoryBySlug($slug)
    {
        $query = "SELECT id_cat, name, description, slug,parent_id FROM es_cat WHERE slug = :slug";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':slug', $slug, PDO::PARAM_STR);
        $result = $sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        $return  = array();
        if( count($rows) != 1 ){
            $return['id_cat'] = 0; $return['name'] = ''; $return['description'] = '';
        }
        else{
            $return = $rows[0];
        }

        return $return;
    }

    public function getHomeContent($file)
    {
        $xml_content = $this->xmlmap->getFilename($file);
        $home_view_data = array();

        foreach ($xml_content as $key => $element){

            if(isset($element['value']) &&  isset($element['type'])){
                $home_view_data[$key] = $this->createHomeElement($element, $key);
            }else{
                foreach ($element as $key2 => $inner_el){
                    $home_view_data[$key][$key2] = $this->createHomeElement($inner_el, $key);
                }
            }


        }

        /*
            *  If there is only one element, add it to its own array.
            */
        if(isset($home_view_data['section']) && isset($home_view_data['section']['category_detail'])){
            $home_view_data['section'] = make_array($home_view_data['section']);
        }
        if(isset($home_view_data['mainSlide']) && isset($home_view_data['mainSlide']['src'])){
            $home_view_data['mainSlide'] = make_array($home_view_data['mainSlide']);
        }


        return $home_view_data;

    }




    private function createHomeElement($element, $key){
        $home_view_data = array();
        if (isset($element['type'])) {
            if ($element['type'] === 'product') {
                $productdata = $this->getProductBySlug($element['value'], false);
                if (!empty($productdata)) {
                    $home_view_data = $productdata;
                }
                else {
                    $home_view_data = array();
                }
            }
            else if ($element['type'] === 'date') {
                $home_view_data = date('M d,Y H:i:s',strtotime($element['value']));
            }
            else if($element['type'] === 'image') {
                if (isset($element['imagemap'])) {
                    $element['imagemap']['coordinate'] = count($element['imagemap']['coordinate'])>0?$element['imagemap']['coordinate']:'';
                    $element['imagemap']['target'] = count($element['imagemap']['target'])>0?$element['imagemap']['target']:'';
                    $home_view_data = array('src' => $element['value'], 'imagemap' => $element['imagemap']);
                }
                else {
                    $home_view_data = array('src' => $element['value']);
                }
            }
            else if ($element['type'] === 'image') {
                $home_view_data = date('M d,Y H:i:s',strtotime($element['value']));
            }
            else if ($element['type'] === 'image') {
                $home_view_data = date('M d,Y H:i:s',strtotime($element['value']));
            }
            else if ($element['type'] === 'image') {
                $home_view_data = date('M d,Y H:i:s',strtotime($element['value']));
            }
            else if (($element['type'] === 'category') || ($element['type'] === 'custom')) {
                if ($element['type'] === 'category') {
                    $home_view_data['category_detail'] = $this->selectCategoryDetails($element['value']);
                    $home_view_data['category_detail']['url'] = 'category/'.$home_view_data['category_detail']['slug'];
                }
                else if ($element['type'] === 'custom') {
                    $home_view_data['category_detail']['imagepath'] = '';
                    $home_view_data['category_detail']['name'] = isset($element['title'])?$element['title']:$element['value'];
                    $home_view_data['category_detail']['url'] = $element['value'];
                }
                $home_view_data['category_detail']['css_class'] = $element['css_class'];
                $home_view_data['category_detail']['subcategory'] = $this->getDownLevelNode($element['value']);
                $home_view_data['category_detail']['layout'] = $element['layout'];

                unset($element['value']);
                unset($element['layout']);
                unset($element['css_class']);
                unset($element['type']);
                unset($element['title']);

                foreach($element as $key=>$cat_el) {
                    if (!isset($cat_el['value']) && !isset($cat_el['type'])) {
                        foreach($cat_el as $inner_key => $cat_inner_el){
                            $home_view_data[$key][$inner_key] =  $this->createHomeElement($cat_inner_el, $inner_key);
                        }
                    }
                    else {
                        $home_view_data[$key] = $this->createHomeElement($cat_el, $key);
                    }
                }
            }
        }
        else {
            $home_view_data = $element['value'];
        }

        return $home_view_data;
    }

    /*
        *    Get the average price of all instances of a sold item between specified dates
        *   @id: product_id
        *   @datefrom: datelimit start
        *   @dateto: datelimit end
        */

    public function get_sold_price($id, $datefrom = '0001-01-01', $dateto = '0001-01-01'){
    if($dateto === '0001-01-01' ){
        $dateto = date('Y-m-d');
    }
        $query = $this->xmlmap->getFilenameID('sql/product','getProductSoldPrice');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id',$id, PDO::PARAM_INT);
        $sth->bindParam(':datefrom',$datefrom, PDO::PARAM_STR);
        $sth->bindParam(':dateto',$dateto, PDO::PARAM_STR);
        $sth->execute();
        $price = $sth->fetch(PDO::FETCH_ASSOC)['sold_price'];
        return $price;
    }

    /*
     *  Calculate promo price. Add the different calculations here.
     *
     *  @baseprice: base price of the product
     *  @start: start datetime of the promo
     *  @end: end datetime of the promo
     *  @type: promo type
     *  @default_percentage: discount percentage during product upload
     */
    public function GetPromoPrice($baseprice, $start, $end, $is_promo, $type, $discount_percentage = 0){
        $today = strtotime( date("Y-m-d H:i:s"));
        $startdate = strtotime($start);
        $enddate = strtotime($end);
        $is_promo = intval($is_promo);
        $result['price'] = $baseprice;
        $bool_start_promo = false;
        $bool_end_promo = false;
        if(intval($is_promo) === 1){
            $promo_array = $this->config->item('Promo')[$type];
            //OPTION CONTAINS PROMO SPECIFIC ADDITIONAL DATA
            $option = isset($promo_array['option'])?$promo_array['option']:array();
            $calculation_id = $promo_array['calculation_id'];
            switch ($calculation_id) {
                case 0 :
                    $PromoPrice = $baseprice;
                    break;
                case 1 :
                    if(($today < $startdate) || ($enddate < $startdate)){
                        $diffHours = 0;
                    }else if($today >= $enddate){
                        $diffHours = 49.5;
                        $bool_start_promo = true;
                    }else{
                        $diffHours = floor(($today - $startdate) / 3600.0);
                        $bool_start_promo = true;
                    }
                    $PromoPrice = $baseprice - (($diffHours * 0.02) * $baseprice);
                    break;
                case 2:
                    if(($today < $startdate) || ($enddate < $startdate) || ($today > $enddate)){
                        $PromoPrice = $baseprice;
                    }else{
                        $PromoPrice = $baseprice - ($baseprice)*($discount_percentage / 100) ;
                        $bool_start_promo = true;
                    }
                    break;
                case 3:
                    $Ymd = strtotime(date('Y-m-d', $today));
                    $His = strtotime(date('H:i:s', $today));
                    if($Ymd === strtotime(date('Y-m-d',$startdate)) ){
                        foreach($option as $opt){
                            if((strtotime($opt['start']) <= $His) && (strtotime($opt['end']) > $His)){
                                $bool_start_promo = true;
                                break;
                            }
                        }
                    }
                    $PromoPrice = $baseprice -   $baseprice*($discount_percentage / 100) ;
                    break;
                case 4 :
                    $PromoPrice = $baseprice;
                    if(!( ($today < $startdate) || ($enddate < $startdate) || ($today > $enddate))){
                        $bool_start_promo = true;
                    }
                    break;
                case 5 :
                    $PromoPrice = $baseprice;
                    if(!( ($today < $startdate) || ($enddate < $startdate) || ($today > $enddate))){
                        $PromoPrice = 0;
                        $bool_start_promo = true;
                    }
                    break;
                case 6 :
                    $PromoPrice = $baseprice;
                    if(!( ($today < $startdate) || ($enddate < $startdate) || ($today > $enddate))){
                        $PromoPrice = 0;
                        $bool_start_promo = true;
                    }
                    break;
                default :
                    $PromoPrice = $baseprice;
                    break;
            }



            if($today > $enddate){
                $bool_end_promo= true;
            }

            $result['price'] = $PromoPrice;
        }
        $result['price'] = (floatval($result['price'])>0)?$result['price']:0.01;
        $result['start_promo'] = $bool_start_promo;
        $result['end_promo'] = $bool_end_promo;

        return $result;
    }

    /**
     *   Check if an item can be purchased based on the purchase limit
     *   @param int $buyer_id: id of the user
     *   @param int $type: promo type
     *   @param bool $start_promo: boolean value whether the promo is active or not
     */
    public function is_purchase_allowed($buyer_id,$type, $start_promo = false)
    {
        $query = "SELECT COALESCE(SUM(op.order_quantity),0) AS `cnt` FROM es_order o
            INNER JOIN es_order_product op ON o.id_order = op.order_id
            INNER JOIN es_product p ON p.id_product = op.product_id AND p.promo_type = :type
            WHERE NOT (o.`order_status` = 99 AND o.`payment_method_id` = 1) AND o.`buyer_id` = :buyer_id ";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':buyer_id',$buyer_id, PDO::PARAM_INT);
        $sth->bindParam(':type',$type, PDO::PARAM_INT);
        $sth->closeCursor();
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $promo = $this->config->item('Promo')[$type];
        if(!$promo){
            $promo = $this->config->item('Promo','promo')[$type];
        }
        if(($result[0]['cnt'] >= $promo['purchase_limit']) ||
            (!$promo['is_buyable_outside_promo'] && !$start_promo)){
            return false;
        }else{
            return true;
        }
    }


    /*******************    NEW PRODUCT UPLOAD STEP 3 FUNCTIONS ***************************************/

    /**
     *  Finalize the product and showed in the listing
     *  @param int $productid
     *  @param int $memberid
     *  @param int $cod
     *  @return BOOL on Success, False on Failed.
     */
    public function finalizeProduct($productid, $memberid, $cod){
        $product = $this->getProductEdit($productid, $memberid);
        if($product){
            $title = $product['name'];
            $slug = $product['slug'];

            if(strlen(trim($slug)) == 0 ){
                $slug = $this->createSlug($title);
                $query = $this->xmlmap->getFilenameID('sql/product', 'finalizeProduct');
                $sth = $this->db->conn_id->prepare($query);
                $sth->bindParam(':slug',$slug ,PDO::PARAM_STR);
            }
            else{
                $query = $this->xmlmap->getFilenameID('sql/product', 'finalizeProductKeepSlug');
                $sth = $this->db->conn_id->prepare($query);
            }
            $sth->bindParam(':productid',$productid,PDO::PARAM_INT);
            $sth->bindParam(':memberid',$memberid,PDO::PARAM_INT);
            $sth->bindParam(':cod', $cod, PDO::PARAM_INT);
            $sth->execute();
            return $slug;
        }
        else{
            return false;
        }
    }

    /**
     *  Function used to store optional data provided in Product Upload Step 3
     *  Returns TRUE on success, FALSE otherwise
     *
     *  @param integer $productID
     *  @param integer $memberID
     *  @param integer $billingID
     *  @param integer $isCOD
     *  @param integer $isMeetup
     *
     *  @return boolean
     */
    public function updateProductUploadAdditionalInfo($productID, $memberID, $billingID, $isCOD, $isMeetup,$shipWithinDays)
    {
        $product = $this->getProductEdit($productID, $memberID);
        if($product){
            $query = $this->xmlmap->getFilenameID('sql/product', 'updateProductUploadAdditionalInfo');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':productid',$productID,PDO::PARAM_INT);
            $sth->bindParam(':memberid',$memberID,PDO::PARAM_INT);
            $sth->bindParam(':is_cod',$isCOD,PDO::PARAM_INT);
            $sth->bindParam(':billing_id', $billingID,PDO::PARAM_INT);
            $sth->bindParam(':is_meetup', $isMeetup, PDO::PARAM_INT);
            $sth->bindValue(':ship_within_days', $shipWithinDays, PDO::PARAM_INT);
            $sth->execute();
            return true;
        }else{
            return false;
        }
    }


    /**
     *  Fetch Billing Details for individual products. Used in displaying summary in step 4.
     *
     *  @param integer $memberID
     *  @param integer $productID
     *
     *  @return array
     */
    public function getProductBillingDetails($memberID, $productID)
    {
        $query = "SELECT COALESCE(p.billing_info_id, 0) as billing_info_id, b.bank_account_name, b.bank_account_number, bank.bank_name
            FROM es_product p
            INNER JOIN es_billing_info b
                ON p.billing_info_id = b.id_billing_info AND b.member_id = :member_id AND p.id_product = :product_id
            INNER JOIN es_bank_info bank
                ON b.bank_id = bank.id_bank";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$memberID,PDO::PARAM_INT);
        $sth->bindParam(':product_id',$productID,PDO::PARAM_INT);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public function update_soldout_status($product_id)
    {
        $query = "
        UPDATE
            es_product
        SET
            `is_sold_out` =
            (SELECT
            IF(SUM(quantity) <= 0, '1', '0') AS soldout
            FROM
            `es_product_item`
            WHERE product_id = :product_id)
        WHERE id_product = :product_id;
        ";
        ;
        $sth0 = $this->db->conn_id->prepare($query);
        $sth0->bindParam(':product_id',$product_id,PDO::PARAM_INT);
        $sth0->execute();

        return true;
    }


    /**
     * Determines if an item is free shipping
     *
     * @param integer $productId
     * @return boolean
     */
    public function is_free_shipping($product_id)
    {
        $query = "SELECT SUM(price) as shipping_total FROM es_product_shipping_head WHERE product_id = :product_id";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_id',$product_id,PDO::PARAM_INT);
        $sth->closeCursor();
        $sth->execute();
        $totalShippingFee = $sth->fetch(PDO::FETCH_ASSOC)['shipping_total'];
        if($totalShippingFee > 0 || !$totalShippingFee){
            return false;
        }
        else{
            return true;
        }
    }

    /**
     *  Used to fetch initial set of products and AJAX requested product list in Feeds page
     *      under category "Featured Products"
     *
     *  @param integer $member_id
     *  @param array $partners_id
     *  @param integer $product_ids
     *  @param integer $per_page
     *  @param integer $page
     *
     *  @return array
     */
    public function getFeaturedProductFeed($member_id,$partners_id,$product_ids,$per_page,$page=0)
    {
        $this->load->library('parser');

        $parseData['partners_id'] = implode(',',$partners_id);
        $parseData['product_ids'] = $product_ids;
        $parseData['limit'] = implode(",", array($page,$per_page));
        $query = $this->xmlmap->getFilenameID('sql/product','getFeaturedProductFeed');
        $query = $this->parser->parse_string($query, $parseData, true);

        $seta = $this->db->conn_id->prepare('SET @a = -1');
        $seta->execute();

        $setb = $this->db->conn_id->prepare('SET @b = 0');
        $setb->execute();

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id',$member_id, PDO::PARAM_INT);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        explodeImagePath($row);

        foreach($row as $k=>$r){
            applyPriceDiscount($row[$k]);
            if($r['imgurl'] === ""){
                $row[$k]['imgurl'] = "assets/user/default/60x60.png";
            }
        }

        return $row;
    }

    /**
     *  Used to fetch initial set of products and AJAX requested product list in Feeds page
     *      under category "New Products"
     *
     *  @param integer $per_page
     *  @param integer $page
     *
     *  @return array
     */
    public function getNewProducts($perPage,$page=0)
    {
        $parseData['limit'] = implode(",", array($page,$perPage));
        $query = $this->xmlmap->getFilenameID('sql/product','getNewProducts');
        $query = $this->parser->parse_string($query, $parseData, true);

        $sth = $this->db->conn_id->prepare($query);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);

        explodeImagePath($row);

        foreach($row as $k=>$r){
            applyPriceDiscount($row[$k]);
            if($r['imgurl'] === ""){
                $row[$k]['imgurl'] = "assets/user/default/60x60.png";
            }
        }

        return $row;
    }

    /**
     *  Fetch static products for Feeds page
     *      (single item "Featured Product", Promo Items, Popular Items)
     *
     *  @param string $string
     *
     *  @return array
     */
    public function getStaticProductFeed($string, $xmlfile)
    {
        switch($string){
            case "popular":
                $node = "feedPopularItems";
                break;
            case "promo":
                $node = "feedPromoItems";
                break;
            case "featured":
                $node = "feedFeaturedProduct";
                break;
        }

        $products = $this->xmlmap->getFilenameNode($xmlfile, $node);

        $data = array();

        foreach( $products as $p ){
            $item = $this->getProductBySlug($p->slug, false);
            $data[]= $item;
        }

        return $data;
    }

    /**
     *  Fetch static banners in Feeds page (left, mid, right)
     *
     *  @return array
     */
    public function getStaticBannerFeed($xmlfile)
    {
        $banner = $this->xmlmap->getFilenameNode($xmlfile, 'feedBanner');
        $b = json_decode(json_encode($banner),true);

        return $b;
    }

 
    public function getProdCount($prodid){
      
        $query = $this->xmlmap->getFilenameID('sql/product','getProdCount');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':prodid',$prodid); 
        $sth->execute(); 
        $number_of_rows = $sth->fetchColumn(); 
        return $number_of_rows;
    }

    /**
     * Check if code exist
     *
     * @param $code
     * @return boolean
     */
    public function validateScratchCardCode($code)
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'validateScratchCardCode');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':code', $code);
        $sth->execute();
        $productFromCode = $sth->fetchAll(PDO::FETCH_ASSOC);
        if ($productFromCode) {
            $product = $this->getProductById($productFromCode[0]['id_product']);
            $quantity = $this->getProductQuantity($productFromCode[0]['id_product'], false, false, true);
            $product['quantity'] = reset($quantity)['quantity'];
            $product['c_id_code'] = $productFromCode[0]['c_member_id'];
        }
        else {
            $product = FALSE;
        }

        return $product;
    }

    /**
     * tie up code to member
     *
     * @param $memberId
     * @param $code
     * @return integer
     */
    public function tieUpMemberToCode($memberId, $code)
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'tieUpMemberToCode');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $memberId);
        $sth->bindParam(':code', $code);
        $sth->execute();
        return $sth->rowCount();
    }

    /**
     * Check if member already joined the promo
     *
     * @Param $productId
     * @Param $memberId
     * @Return boolean
     */
    public function validateMemberForBuyAtZeroPromo($productId, $memberId)
    {
        $query = $this->xmlmap->getFilenameID('sql/product', 'buyAtZeroAuthenticate');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':productId', $productId);
        $sth->bindParam(':memberId', $memberId);
        $sth->execute();
        $cnt = $sth->fetchAll(PDO::FETCH_ASSOC)[0]['cnt'];
        $result = true;
        if($cnt >= 1){
            $result = false;
        }

        return $result;
    }
    
    /**
     * Join member to buyAtZero php promo
     *
     * @Param $productId
     * @Param $memberId
     * @Return boolean
     */
    public function registerMemberForBuyAtZeroPromo($productId, $memberId)
    {
        $auth = $this->validateMemberForBuyAtZeroPromo($productId, $memberId);

        if($auth == false){
            return false;
        }
        $query = $this->xmlmap->getFilenameID('sql/product', 'buyAtZeroRegistration');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':productId', $productId);
        $sth->bindParam(':memberId', $memberId);
        $sth->bindParam(':date', date('Y-m-d H:i:s'));
        $sth->execute();

        return true;
    }


    public function getProdCountBySlug($slug)
    {
      
        $query = $this->xmlmap->getFilenameID('sql/product','getProdCountBySlug');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':slug',$slug); 
        $sth->execute(); 
        $number_of_rows = $sth->fetchColumn(); 
        return $number_of_rows;
    }        
        

}

/* End of file product_model.php */
/* Location: ./application/models/product_model.php */

