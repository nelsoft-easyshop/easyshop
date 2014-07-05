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
	    $this->load->config("promo");
	}

	# the queries directory -- application/resources/sql/product.xml

	function selectCategoryDetails($id) 
	{
	    $query = $this->xmlmap->getFilenameID('sql/product', 'selectCategoryDetails');
	    $sth = $this->db->conn_id->prepare($query);
	    $sth->bindParam(':id_cat', $id);
	    $sth->execute();
	    $row = $sth->fetchAll(PDO::FETCH_ASSOC);
    
	    return $row[0];
	}

	function getDownLevelNode($id, $is_admin = false) # get all down level category on selected category from database
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

	function selectChild($id) # get all down level category on selected category from database
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
	
	function getParentId($id) #get all parent category from selected id.
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

	function getAttributesBySelf($selfId) # get all attributes from all parents from to the last selected category
	{

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
	      AND a.cat_id = :cat_id
	      GROUP BY cat_name, a.id_attr, a.attr_lookuplist_id, input_type, input_name
	    ORDER BY cat_name ASC ";

	    $sth = $this->db->conn_id->prepare($query);
	    $sth->bindParam(':cat_id',$selfId,PDO::PARAM_INT);
	    $sth->execute();
	    $row = $sth->fetchAll(PDO::FETCH_ASSOC);
	    return $row;

	## need to loop so i do it manual to generate dynamic query (but not advisable please dont do this)
	## - Prepared by: Ryan Vasquez
	}

	function getLookItemListById($id) # getting item list from database. EG: Color -- (White,Blue,Yellow)
	{
	    $query = $this->xmlmap->getFilenameID('sql/product','getLookupListItem');
	    $sth = $this->db->conn_id->prepare($query);
	    $sth->bindParam(':id',$id);
	    $sth->execute();
	    $row = $sth->fetchAll();

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
    

    function getBrandById($brand_id)
	{
        if(is_array($brand_id)){
            if(count($brand_id) === 0){
                return false;
            }
            $query =  'SELECT name, id_brand, image, description FROM `es_brand` WHERE id_brand IN ';
            $qmarks = implode(',', array_fill(0, count($brand_id), '?'));
            $query = $query.'('.$qmarks.')';
            $sth = $this->db->conn_id->prepare($query);
            $k = 0;
            foreach ($brand_id as $id){
                $sth->bindValue(($k+1), $id, PDO::PARAM_INT);   
                $k++;
            }                  
        }else{
            $query = $this->xmlmap->getFilenameID('sql/product', 'getBrandById');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':brand_id',$brand_id,PDO::PARAM_INT);
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
 			$concatQuery .= " AND  ( a.`name` LIKE :like".$key." OR `keywords` LIKE :like".$key." )";
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

    function finalizeProduct($productid, $memberid,$billing_id, $is_cod){
        $product = $this->getProductEdit($productid, $memberid);
        if($product){
            $title = $product['name'];
            $slug = $product['slug'];
            
            if(strlen(trim($slug)) == 0 ){
                $slug = $this->createSlug($title);
                $query = $this->xmlmap->getFilenameID('sql/product', 'finalizeProduct');
                $sth = $this->db->conn_id->prepare($query);
                $sth->bindParam(':slug',$slug ,PDO::PARAM_STR);
            }else{
                $query = $this->xmlmap->getFilenameID('sql/product', 'finalizeProductKeepSlug');
                $sth = $this->db->conn_id->prepare($query);
            }
            $sth->bindParam(':productid',$productid,PDO::PARAM_INT);
            $sth->bindParam(':memberid',$memberid,PDO::PARAM_INT);
            $sth->bindParam(':is_cod',$is_cod,PDO::PARAM_INT);
            $sth->bindParam(':billing_id', $billing_id,PDO::PARAM_INT);
            $sth->execute();
            return true;
        }else{
            return false;
        }
	}
    
    /*
     *    Function that returns products within a category and applies all available filters
     */
	function getProductsByCategory($categories,$conditionArray=array(),$countMatch=0,$operator = "<",$start=0,$per_page= PHP_INT_MAX ,$sortString = '',$words = array())
	{	
		$concatQuery = "";
		$arrayCount = count($conditionArray);
		if ($arrayCount > 0) {
			krsort($conditionArray);
			if(!empty($conditionArray['attributes'])) {  
				arsort($conditionArray['attributes']);
			}
		}

		if(count($words) > 0){
			
			foreach ($words as $key => $value) {
				$concatQuery .= " AND  ( `name` LIKE :like".$key." OR `keywords` LIKE :like".$key." )";
			}
		}

		$condition_string = "";
		$attributeString = "";
		$brand_string = "";  		
		$start = (int)$start;
		$per_page = (int)$per_page;
		$havingString = ""; 

		if ($arrayCount > 0) {
			foreach ($conditionArray as $key => $value) {
				if($key == 'attributes'){
					$attrCount = count($conditionArray['attributes']);
				 	$counterAttrName = 0;
				 	$counterAttrValue = 0;
					foreach ($conditionArray[$key] as $keyatt => $valueatt) {
						$count = count($conditionArray[$key][$keyatt]); 

						if($count == 1){
							$attributeString .= " OR    ( `attr_name` = :attrname$counterAttrName AND attr_value = :attrvalue$counterAttrValue )  ";
							$counterAttrValue++;
						}else{
							foreach ($conditionArray[$key][$keyatt] as $key2 => $value2) {
								$attributeString .= " OR    ( `attr_name` = :attrname$counterAttrName AND attr_value = :attrvalue$counterAttrValue )  ";
								$counterAttrValue++;
							}
						}
						$counterAttrName++;
					}

				}elseif($key == 'brand'){
					if($conditionArray[$key]['count'] == 1){
						$condition_string .= " AND brand = :brand";
					}else{
						$condition_string .= " AND brand IN (";
						foreach ($conditionArray[$key]['value'] as $keybrand => $valuebrand) {
							$brand_string .= ",:brand".$keybrand;
						}
						$brand_string = ltrim ($brand_string,',');
						$condition_string .= $brand_string. ")";
					}
				}elseif($key == 'condition'){
					$condition_string .= " AND `condition` = :condition";
				}elseif($key == 'price'){
					$condition_string .= " AND price BETWEEN :startprice AND :endprice";
				}
			}
		}  
		
		if($countMatch > 0){
			// $havingString = " HAVING cnt_all ".$operator. $countMatch." ";
			$attributeString = substr_replace($attributeString," AND",1,3); 
			$condition_string .= $attributeString;	
		}

	

		$query = $this->xmlmap->getFilenameID('sql/product', 'getProducts');
		$query = $query."
		 WHERE cat_id IN (".$categories.")  ".$concatQuery." 
		 AND is_delete = 0 AND is_draft = 0
		 ".$condition_string."
		 GROUP BY product_id , `name`,price,`condition`,brief,product_image_path,
         item_list_attribute.is_new, item_list_attribute.is_hot, item_list_attribute.clickcount,item_list_attribute.slug,
         item_list_attribute.brand_id,   item_list_attribute.`promo_type`, item_list_attribute.`is_promote`,
         item_list_attribute.`startdate`, item_list_attribute.`enddate`  , item_list_attribute.`discount`         
         ".$havingString."
  	   	 ORDER BY ".$sortString." cnt_all DESC, `name` ASC
		 LIMIT :start, :per_page 
		 ";
	


		$sth = $this->db->conn_id->prepare($query); 
	 
			if(count($words) > 0){

				foreach ($words as $key => $value) {
					$newValue = '%'.$value.'%';
					$sth->bindParam(':like'.$key,$newValue,PDO::PARAM_STR);
				}
			}

			if ($arrayCount > 0) {
			foreach ($conditionArray as $key => $value) {
				if($key == 'attributes'){

					$attrCount = count($conditionArray['attributes']);
				 	$counterAttrName = 0;
				 	$counterAttrValue = 0;
					foreach ($conditionArray[$key] as $keyatt => $valueatt) {

						$count = count($conditionArray[$key][$keyatt]); 
						$attrNameBind = ':attrname'.$counterAttrName;
					 	$sth->bindParam($attrNameBind,$keyatt,PDO::PARAM_STR);

						if($count == 1){
							$attrValueBind = ':attrvalue'.$counterAttrValue;
							$sth->bindParam($attrValueBind,$valueatt,PDO::PARAM_STR);
						 
							$counterAttrValue++;		 
						}else{
						 	 
							foreach ($conditionArray[$key][$keyatt] as $key2 => $value2) {
								$attrValueBind = ':attrvalue'.$counterAttrValue;
								$sth->bindParam($attrValueBind,$value2,PDO::PARAM_STR);
							 
								$counterAttrValue++;

							}
						}
						$counterAttrName++;
					}

				}elseif($key == 'brand'){

					if($conditionArray[$key]['count'] == 1){
						$brandValue = $conditionArray[$key]['value'];
						$sth->bindParam(':brand',$brandValue,PDO::PARAM_STR);				 
					}else{
						foreach ($conditionArray[$key]['value'] as $keybrand => $valuebrand) {	  
							$sth->bindParam(":brand".$keybrand,$valuebrand,PDO::PARAM_STR);
						}
					}
				}elseif($key == 'condition'){

					$conditionValue = $conditionArray[$key]['value']; 
					$sth->bindParam(':condition',$conditionValue,PDO::PARAM_STR);
							 
				}elseif($key == 'price'){

					$priceStartValue = $conditionArray[$key]['start']; 
					$priceEndValue = $conditionArray[$key]['end'];  
					$sth->bindParam(':startprice',$priceStartValue,PDO::PARAM_STR);		 
					$sth->bindParam(':endprice',$priceEndValue,PDO::PARAM_STR);
				
				}
			}  
		}
 
		$sth->bindParam(':start',$start,PDO::PARAM_INT);			 
		$sth->bindParam(':per_page',$per_page,PDO::PARAM_INT);
        
		$sth->execute(); 

		$products = $sth->fetchAll(PDO::FETCH_ASSOC);	
        explodeImagePath($products);
        for($k = 0; $k<count($products); $k++){
            $products[$k]['id_product'] = $products[$k]['product_id'];
            applyPriceDiscount($products[$k]);
        }

		return $products;
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
        
        foreach($rows as $idx => $row){
            applyPriceDiscount($row);
            $rows[$idx] = $row;
        }

        explodeImagePath($rows);
		foreach($rows as $key=>$row){
			if(intval($row['id_product']) == intval($prod_id)){
				unset($rows[$key]);
			}
		}
		return array_values($rows);
	}
	
	function getProductById($id, $extended = false){
	    
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
		
	function getProductCount($down_cat){
		$qmarks = implode(',', array_fill(0, count($down_cat), '?'));
		$query = $this->xmlmap->getFilenameID('sql/product','getProductCount');
		$query = $query.'('.$qmarks.')';
		$sth = $this->db->conn_id->prepare($query);
		$sth->execute($down_cat);
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	
	function getProductEdit($product_id, $member_id){
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
    
    function editProduct($product_details=array(),$member_id){
        $query = $this->xmlmap->getFilenameID('sql/product','editProduct');

        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':name',$product_details['name']);
        $sth->bindParam(':sku',$product_details['sku']);
        $sth->bindParam(':brief',$product_details['brief']);
        $sth->bindParam(':description',$product_details['description']);
        $sth->bindParam(':keywords',$product_details['keyword']);
        $sth->bindParam(':brand_id',$product_details['brand_id']);  
        $sth->bindParam(':style_id',$product_details['style_id']);
        $sth->bindParam(':price',$product_details['price']);
        $sth->bindParam(':condition',$product_details['condition']);
        $sth->bindParam(':p_id',$product_details['product_id']);
	$sth->bindParam(':member_id',$member_id);
        $sth->bindParam(':brand_other_name',$product_details['brand_other_name']);
        $sth->bindParam(':modifieddate', date('Y-m-d H:i:s'));
        $sth->bindParam(':discount', $product_details['discount']);
        $sth->bindParam(':search_keyword', $product_details['search_keyword']);
        
        $bool = $sth->execute();
		
	return $sth->rowCount();
    }
    
    function editProductCategory($cat_id,$product_id,$member_id, $other_cat_name = ''){
       
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

    public function getProductQuantity($product_id, $verbose = false, $check_lock = false){
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
                $data[$row['id_product_item']]['quantity'] = $row['quantity'];
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
        

        $query = 'SELECT lck.id_item_lock, pi.id_product_item, lck.qty as lock_qty, lck.timestamp, NOW() as timenow,
        pi.quantity FROM es_product_item_lock lck INNER JOIN es_product_item pi ON pi.product_id = :product_id AND lck.product_item_id = pi.id_product_item';
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_id',$product_id, PDO::PARAM_INT);
        $sth->execute();
        $lockdata = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach($lockdata as $lock){
            //DELETE LOCK ITEMS THAT ARE 5 MINUTES IN LIFE TIME
            if(round((strtotime($lock['timenow']) - strtotime($lock['timestamp']))/60) > 10){
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
    /***********************	STEP 3 PRODUCT UPLOAD 	*****************************/
    /********************************************************************************/

    /**
    *	Fetch Locations from location lookup table to fill dropdown listbox
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
     *	Fetch Product Attr Combinations based on product ID and from Product Upload Step 2
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
    *	Store Shipping Price in `es_product_shipping_head`
    *	Table contains -> Location ID vs Price
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
     *	Store Product Shipping Mapping in `es_product_shipping_details`
     *	Table contains -> Mapping of ShippingID vs ProductItemAttrID
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
	 *	Store shipping preference head
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
	 *	Store shipping preference detail
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
	 *	Check if shipping preference ID sent matches current user
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
        
		$data = array();
		$data['id_product_item'] = array();
		$data['has_shippingsummary'] = false;
		
	// Every attribute combination should have shipping location/price
	// Checking first entry is sufficient to determine if shipping details exist for a product
		foreach($row as $r){
			// Create array for list of product items
			if ( !in_array($r['id_product_item'], $data['id_product_item']) ) {
				$data['id_product_item'][] = $r['id_product_item'];
			}
			// Set has_shippingsummary to true (once only) if fetched product item has location
			if( !$data['has_shippingsummary'] && $r['id_location'] != '' ){
				$data['has_shippingsummary'] = true;
			}
			// If product item has assigned location
			if( $r['id_location'] != '' ){
				// Set location id VS price
				if ( !isset($data[$r['id_product_item']][$r['id_location']]) ) {
					$data[$r['id_product_item']][$r['id_location']] = $r['price'];
				}
				// Set location name
				if ( !isset($data['location']['id_location']) ) {
					$data['location'][$r['id_location']] = $r['location'];
				}
			}
		}
		
    	return $data;
	}
	
	/*
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
			if(!isset($data[$r['id_product']][$r['location_id']])){
				$data[$r['id_product']][$r['location_id']] = $r['price'];
			}
			if(!isset($data['name'][$r['id_product']])){
				$data['name'][$r['id_product']] = $r['name'];
                $data['brief'][$r['id_product']] = $r['brief'];
                $data['date'][$r['id_product']] = date('M-d-Y',strtotime($r['lastmodifieddate']));
			}
		}
		
		return $data;
	}*/
	
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
			if( !isset($data[$r['head_id']]['title']) ){
				$data['name'][$r['head_id']] = $r['title'];
			}
			$data[$r['head_id']][$r['location_id']] = $r['price'];
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
    
	public function getCategoryBySlug($slug)
	{
		$query = "SELECT id_cat, name, description, slug FROM es_cat WHERE slug = :slug";
    	$sth = $this->db->conn_id->prepare($query);
    	$sth->bindParam(':slug', $slug, PDO::PARAM_STR);
    	$result = $sth->execute();
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        $return  = array();
		if( count($rows) != 1 ){
			$return['id_cat'] = 0; $return['name'] = ''; $return['description'] = '';
		}else{
            $return = $rows[0];
        }
		
        return $return;  
	}
        
    public function getHomeContent($devfile = 'page/home_files_dev', $prodfile = 'page/home_files_prod'){ 
	$file = ES_PRODUCTION?$prodfile:$devfile;
	
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
         *  If there is only one section element, add it to its own array.
         */
        if(isset($home_view_data['section']) && isset($home_view_data['section']['category_detail'])){
            $temp = $home_view_data['section'];
            $home_view_data['section'] = array();
            $home_view_data['section'][0] = $temp;
        }
        return $home_view_data;

    }
    
    private function createHomeElement($element, $key){
        $home_view_data = array();
        if($element['type'] === 'product'){
            $productdata = $this->getProductBySlug($element['value'], false);
            if (!empty($productdata)){
                $home_view_data = $productdata;                
            }
            else{
                $home_view_data = array();
            }
        }else if($element['type'] === 'date'){
            $home_view_data = date('M d,Y H:i:s',strtotime($element['value']));
        }else if($element['type'] === 'image'){
            if(isset($element['imagemap'])){    
                $element['imagemap']['coordinate'] = count($element['imagemap']['coordinate'])>0?$element['imagemap']['coordinate']:'';
                $element['imagemap']['target'] = count($element['imagemap']['target'])>0?$element['imagemap']['target']:'';
                $home_view_data = array('src' => $element['value'], 'imagemap' => $element['imagemap']);
            }
            else{
                $home_view_data = date('M d,Y H:i:s',strtotime($element['value']));
            }
        }else if(($element['type'] === 'category') || ($element['type'] === 'custom')) { 
            if($element['type'] === 'category'){
               $home_view_data['category_detail'] = $this->selectCategoryDetails($element['value']);
               $home_view_data['category_detail']['url'] = 'category/'.$home_view_data['category_detail']['slug'];
            }
            else if($element['type'] === 'custom'){
               $home_view_data['category_detail']['imagepath'] = '';
               $home_view_data['category_detail']['name'] = isset($element['title'])?$element['title']:$element['value'];
               $home_view_data['category_detail']['url'] = 'vendor/'.$element['value'];
            }
            $home_view_data['category_detail']['css_class'] = $element['css_class'];
            $home_view_data['category_detail']['subcategory'] = $this->getDownLevelNode($element['value']);
            $home_view_data['category_detail']['layout'] = $element['layout'];
            
            unset($element['value']);
            unset($element['layout']);
            unset($element['css_class']);
            unset($element['type']);
            unset($element['title']);
            
            foreach($element as $key=>$cat_el){
                if(is_array($cat_el)){
                    foreach($cat_el as $inner_key => $cat_inner_el){
                        $home_view_data[$key][$inner_key] =  $this->createHomeElement($cat_inner_el, $inner_key);
                    }
                }else{
                    $home_view_data[$key] = $this->createHomeElement($cat_el, $key);
                }

            }
        }else{
            $home_view_data = $element['value'];            
        }
        
        return $home_view_data;
    }
    
    public function is_sold_out($id){
        $product_quantity = $this->getProductQuantity($id);
        $is_sold_out = true;
        foreach($product_quantity as $q){
            if($q['quantity'] > 0){
                $is_sold_out = false;
                break;
            }
        }
        return $is_sold_out;
    }
    
    public function get_sold_price($id, $datefrom = '0001-01-01', $dateto = '0001-01-01'){
        if($dateto === '0001-01-01'){
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

    
    public function GetPromoPrice($baseprice,$discount,$start,$end,$is_promo,$type,$buyer_id, $product_id){
        $today = strtotime( date("Y-m-d H:i:s"));
        $startdate = strtotime($start);
        $enddate = strtotime($end);
        $is_promo = intval($is_promo);
        $result['price'] = $baseprice;
        $result['can_purchase']  = true;
        $result['sold_price'] = 0;
        $result['is_soldout'] = false;

        if($is_promo === 1){
            $calculation_id = $this->config->item('Promo')[$type]['calculation_id'];
            $LimitPerProductId = false;
            switch ($calculation_id) {
                case 0 :
                    $PromoPrice = $baseprice;
                    break;
                case 1 :
                    if(($today < $startdate) || ($enddate < $startdate)){
                        $diffHours = 0;
                    }else if($today >= $enddate){
                        $diffHours = 49.5;
                    }else{
                        $diffHours = floor(($today - $startdate) / 3600.0);
                    }
                    $PromoPrice = $baseprice - (($diffHours * 0.02) * $baseprice);
                    break;
                case 2:
                    if(($today < $startdate) || ($enddate < $startdate) || ($today > $enddate)){
                        $PromoPrice = $baseprice;
                    }else{
                        $PromoPrice = $baseprice - ($baseprice)*0.20;
                    }
                    break;
                case 3:
                    #items should be can_purchase = 1 AND start_promo = 1 to be sell
                    $LimitPerProductId = $product_id;
                    $PromoPrice = $baseprice -   $baseprice*($discount / 100) ;
                    break;
                default :
                    $PromoPrice = $baseprice;
                    break;
            }

            $result['price'] = $PromoPrice;
            $result['can_purchase'] = $this->is_purchase_allowed($buyer_id,$type,$LimitPerProductId, $startdate,$enddate);
            $result['is_soldout'] = $this->is_sold_out($product_id);
            $result['sold_price'] = $this->get_sold_price($product_id, date('Y-m-d',$startdate), date('Y-m-d',$enddate));
        }
        $result['price'] = (floatval($result['price'])>0)?$result['price']:0.01;

        return $result;
   }


    public function is_purchase_allowed($buyer_id,$type,$product_id=false,$start,$end){
        $condition = !($product_id===false) ? " AND  op.`product_id` = :product_id " : " AND  o.`buyer_id` = :buyer_id ";
        $query = "
            SELECT
            COALESCE(SUM(op.order_quantity),0) AS `cnt` FROM es_order o
            INNER JOIN es_order_product op ON o.id_order = op.order_id
            INNER JOIN es_product p ON p.id_product = op.product_id AND p.promo_type = :type
            WHERE NOT (o.`order_status` = 99 AND o.`payment_method_id` = 1) " . $condition;
        $sth = $this->db->conn_id->prepare($query);
        !($product_id===false) ? $sth->bindParam(':product_id',$product_id, PDO::PARAM_INT) : $sth->bindParam(':buyer_id',$buyer_id, PDO::PARAM_INT);
        $sth->bindParam(':type',$type, PDO::PARAM_INT);
        $sth->closeCursor();
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $promo = $this->config->item('Promo')[$type];
        $PurchaseLimit = $promo['purchase_limit'];
        if(is_string($PurchaseLimit)){
            $PurchaseLimit = $promo[$PurchaseLimit];
            foreach($PurchaseLimit as $items){
                if(($start > strtotime($items['start'])) || $end < strtotime($items['end']) ) {
                    $PurchaseLimit = 0;
                }else{
                    $PurchaseLimit = $items['purchase_limit'];
                }
            }
        }
        if($result[0]['cnt'] >= $PurchaseLimit){
            return false;
        }else{
            return true;
        }
    }

    public function check_if_soldout($product_id)
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

    
}
