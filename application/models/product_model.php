<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class product_model extends CI_Model
{
	function __construct() 
	{
		parent::__construct();  
        $this->load->library("sqlmap");
	}

	# the queries directory -- application/resources/sql/product.xml

	function selectCategoryDetails($id) # get all down level category on selected category from database
	{
		$query = $this->sqlmap->getFilenameID('product', 'selectCategoryDetails');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id_cat', $id);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
 	
		return $row[0];
	}

	function getDownLevelNode($id) # get all down level category on selected category from database
	{
		$query = $this->sqlmap->getFilenameID('product', 'selectDownLevel');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':parent_id', $id);
		$sth->execute();
		$row = $sth->fetchAll();
		return $row;
	}

	function selectChild($id) # get all down level category on selected category from database
	{
		$query = $this->sqlmap->getFilenameID('product', 'selectChild');
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

		$query = $this->sqlmap->getFilenameID('product','getParent');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$id);
		$sth->execute();
     
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $row;
	}
    
    function getParentIdByProduct($product_id, $member_id) #get all parent category from selected product
	{
        $query = $this->sqlmap->getFilenameID('product','getProductCategory');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$product_id);
        $sth->bindParam(':member_id',$member_id);
		$sth->execute();
		$p_row = $sth->fetch(PDO::FETCH_ASSOC);
        $row = array();
        if($sth->rowCount() > 0){
            $query = $this->sqlmap->getFilenameID('product','getParent');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':id',$p_row['cat_id']);
            $sth->execute();
            $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
		return $row;
	}

    function getCategoryDetails($id){
        $query = $this->sqlmap->getFilenameID('product','getCategoryDetails');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$id);
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);
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
		$query = $this->sqlmap->getFilenameID('product','getLookupListItem');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$id);
		$sth->execute();
		$row = $sth->fetchAll();

		return $row;
	}

	function getSlug($id) 
	{
		$query = $this->sqlmap->getFilenameID('product', 'getSlugByID');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$id);
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		return $row['slug'];
	}
    
    /*  DO NOT USE THIS FUNCTION FOR ANYTHING OTHER THAN DISPLAYING THE PRODUCT PAGE.
     *  THIS INCREMENTS THE PRODUCT CLICK COUNT. USE getProductByID FOR ANYTHING ELSE.
     */
    
    function getProductBySlug($slug) 
	{
		$query = $this->sqlmap->getFilenameID('product', 'getProductBySlug');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':slug',$slug);
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);
        if(intval($row['o_success']) !== 0){
            if(strlen(trim($row['userpic']))===0)
                 $row['userpic'] = 'assets/user/default';
            if(intval($row['brand_id'],10) === 1)
                $row['brand_name'] = ($row['custombrand']!=='')?$row['custombrand']:'Custom brand';
        }  
        $row['original_price'] = $row['price'];
        $row['price'] = $this->GetPromoPrice($row['price'],$row['startdate'],$row['enddate'],$row['is_promote'],$row['promo_type']);

		return $row;
	}
    
    function getProductPreview($id, $memberid, $is_draft = 1){
        $query = $this->sqlmap->getFilenameID('product', 'getProductPreview');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':product_id',$id);
        $sth->bindParam(':member_id',$memberid);
        $sth->bindParam(':is_draft',$is_draft);
        $sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);
        if(count($row) > 0){
            if(strlen(trim($row['userpic']))===0){
                 $row['userpic'] = 'assets/user/default';
            }
        }
        
        return $row;
    }


    /*
     * Feb 20, 2014 Edit: Enclosed name key with single quotes. This is to prevent the id keys from 
     * being mixed-up with numeric name keys when using $key = 'ALL'
     */
	function getProductAttributes($id, $key = 'ALL') # getting the product attribute using product ID
	{	
		$query = $this->sqlmap->getFilenameID('product', 'getProductAttributes');

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
			$this->explodeImagePath($temp,true);
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
		$query = $this->sqlmap->getFilenameID('product', 'getProductImages');

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$id);
		$sth->execute();
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		$this->explodeImagePath($rows);
		
		if($getById){
			$rowsById = array();
			foreach($rows as $row){
				$rowsById[$row['id_product_image']] = $row;
			}
			$rows = $rowsById;
		}
		return $rows;
	}
       
	// // start of new 
	function getProductsByCategory($categories,$conditionArray,$countMatch,$operator = "<",$start,$per_page,$sortString,$words = array())
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

	

		$query = $this->sqlmap->getFilenameID('product', 'getProducts');
		$query = $query."
		 WHERE cat_id IN (".$categories.")  ".$concatQuery." 
		 AND is_delete = 0 AND is_draft = 0
		 ".$condition_string."
		 GROUP BY product_id , `name`,price,`condition`,brief,product_image_path,
         item_list_attribute.is_new, item_list_attribute.is_hot, item_list_attribute.clickcount,item_list_attribute.slug 
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
        
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);	

		return $rows;
	}

	function getProductAttributesByCategory($ids)
	{
		
		$query = $this->sqlmap->getFilenameID('product', 'getProductAndAttributes');

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
		$query = $this->sqlmap->getFilenameID('product', 'getBrandsByCategory');
		
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':cat_id',$cat_id);
		$sth->execute();
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		return $rows;
	}
	
	function getAvailableBrand($cat_id)
	{
		$query = $this->sqlmap->getFilenameID('product', 'getAvailableBrand');
		
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':cat_id',$cat_id);
		$sth->execute();
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		return $rows;
	}
    
    
    function getBrandName($brand_id)
	{
		$query = $this->sqlmap->getFilenameID('product', 'getBrandName');
		
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':brand_id',$brand_id,PDO::PARAM_INT);
		$sth->execute();
        $return = $sth->fetch(PDO::FETCH_ASSOC);
        
		return ($sth->rowCount()>0)?$return['name']:false;
	}


	function selectAttributeNameWithNameAndId($name,$id)
	{
		$query = $this->sqlmap->getFilenameID('product', 'selectAttributeNameWithNameAndId');
		
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':name',$name);
		$sth->bindParam(':id',$id,PDO::PARAM_INT);
		$sth->execute();
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		return $rows;	
	}
    
    function selectAttributeNameWithTypeAndId($groupID,$datatypeID)
	{
		$query = $this->sqlmap->getFilenameID('product', 'selectAttributeNameWithTypeAndId');
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


	function addNewProduct($product_title,$sku,$product_brief,$product_description,$keyword,$brand_id,$cat_id,$style_id,$member_id,$product_price,$product_discount,$product_condition,$other_category_name, $other_brand_name)
	{

		# this function for adding new product to es_product table.
		$query = $this->sqlmap->getFilenameID('product','addNewProduct_es_product');
		
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
        
		$bool = $sth->execute();
        
        if(!$bool){
            $errorInfo = $sth->errorInfo();
            log_message('error', 'PDO::ADD: 0=>'. $errorInfo[0]);
            log_message('error', 'PDO::ADD: 1=>'. $errorInfo[1]);
            log_message('error', 'PDO::ADD: 2=>'. $errorInfo[2]);
        }

		return $this->db->conn_id->lastInsertId('id_product');
	}

	function addNewAttributeByProduct($product_id,$attribute_id,$value,$price)
	{
		# this function for adding new attribute of the product to es_product_attr table.
		$query = $this->sqlmap->getFilenameID('product','addNewAttribute');

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
		$query = $this->sqlmap->getFilenameID('product','addNewAttributeOtherName');

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':product_id',$product_id);
		$sth->bindParam(':field_name',$name);
		$sth->execute();

		return $this->db->conn_id->lastInsertId('id_product_attr');
	}

	function addNewAttributeByProduct_others_name_value($others_id,$name,$price,$imageid)
	{
		# this function for adding new attribute of the product to es_product_attr table.
		$query = $this->sqlmap->getFilenameID('product','addNewAttributeOtherNameValue');

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
		$query = $this->sqlmap->getFilenameID('product','addNewProductImage');

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
		$query = $this->sqlmap->getFilenameID('product','addNewCombination');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':product_id',$product_id,PDO::PARAM_INT);
		$sth->bindParam(':qty',$qty,PDO::PARAM_INT);	
		$sth->execute();
		return $this->db->conn_id->lastInsertId('id_product_item');
	}
    
    function updateCombination($product_id,$product_item_id,$qty)
	{
		$query = $this->sqlmap->getFilenameID('product','updateCombination');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':product_id',$product_id,PDO::PARAM_INT);
        $sth->bindParam(':product_item_id',$product_item_id,PDO::PARAM_INT);	
		$sth->bindParam(':qty',$qty,PDO::PARAM_INT);	
        $sth->execute();
	}
    
    function updateCombinationAttribute($product_id_item,$product_attr_id,$other_identifier, $product_item_attr_id)
	{
		$query = $this->sqlmap->getFilenameID('product','updateCombinationAtrribute');
		$sth = $this->db->conn_id->prepare($query);

		$sth->bindParam(':product_id_item',$product_id_item,PDO::PARAM_INT);
		$sth->bindParam(':product_attr_id',$product_attr_id,PDO::PARAM_INT);
		$sth->bindParam(':is_other',$other_identifier,PDO::PARAM_INT);	
        $sth->bindParam(':product_item_attr_id',$product_item_attr_id,PDO::PARAM_INT);	
		$sth->execute();
	}

    function selectProductItemAttr($product_item_id,$product_attr_id, $is_other){
        $query = $this->sqlmap->getFilenameID('product','selectProductItemAttr');
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
		$query = $this->sqlmap->getFilenameID('product','selectProductAttribute');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':lookupId',$attribute_id,PDO::PARAM_INT);
		$sth->bindParam(':productID',$product_id,PDO::PARAM_INT);	
		$sth->execute();
		$rows = $sth->fetch(PDO::FETCH_ASSOC);
		 
		return $rows['id_product_attr'];	
	}

	function selectProductAttributeOther($other_group,$other_value,$product_id)
	{
		$query = $this->sqlmap->getFilenameID('product','selectProductAttributeOther');
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
		$query = $this->sqlmap->getFilenameID('product','addNewCombinationAtrribute');
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
		$query = $this->sqlmap->getFilenameID('product','getAttributeByCategoryIdWithDistinct');

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':cat_id',$id); 
		$sth->execute();

		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $row;
	}
	
	function getAttributeByCategoryIdWithName($id,$name)
	{
		
		$query = $this->sqlmap->getFilenameID('product','getAttributeByCategoryIdWithName');

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':cat_id',$id); 
		$sth->bindParam(':name',$name); 
		$sth->execute();

		$row = $sth->fetchAll(PDO::FETCH_COLUMN, 0);

		return $row;
	}


	function insertSearch($value)
	{
		$query = "INSERT INTO es_keywords_temp (keywords) VALUES(:value)"; 
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':value',$value,PDO::PARAM_STR);
        $sth->execute();
       
        $row = $sth->fetch(PDO::FETCH_ASSOC);
  
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
		$query = $this->sqlmap->getFilenameID('product','submitReview');

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
			$query = $this->sqlmap->getFilenameID('product','getRecentProductReviews');
			$sth = $this->db->conn_id->prepare($query);
			$sth->bindParam(':id',$productid);	
			$sth->execute();
			$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		else{
			$query = $this->sqlmap->getFilenameID('product','getMoreProductReviews');
			$sth = $this->db->conn_id->prepare($query);
			$sth->bindParam(':id',$productid);	
			$sth->bindParam(':last_id',$last_id);
			$sth->execute();
			$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		return $row;
	}
	
	function getReviewReplies($temp, $id){
		$query = $this->sqlmap->getFilenameID('product','getReviewReplies');
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
		$query = $this->sqlmap->getFilenameID('product','getAllowedReviewers');
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
	
	function getCurrUserDetails($uid)
	{
		$query = $this->sqlmap->getFilenameID('users','getUserDetails');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$uid);	
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);

		return $row;
	}

	function getVendorRating($uid)
	{
		$query = $this->sqlmap->getFilenameID('users','getUserRating');
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

	function getFirstLevelNode($is_main = false, $is_alpha = false) # get all main/parent/first level category from database
	{
        $query = $this->sqlmap->getFilenameID('product', 'selectFirstLevel');
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
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC); 

		return $row;
	}

	function itemSearchNoCategory($words,$start,$per_page)
	{
		$start = (int)$start;
	 	$per_page = (int)$per_page;
 		$concatQuery = "";
 		foreach ($words as $key => $value) {
 			$concatQuery .= " AND  ( `name` LIKE :like".$key." OR `keywords` LIKE :like".$key." )";
 		}
  
		$query = "
	 	SELECT 
	      main_tbl.*
	      , es_product_image.product_image_path 
	    FROM
	      `es_product_image` 
	      LEFT JOIN 
	        (SELECT 
	          `id_product` AS product_id
	          , `slug` as product_slug
	          , `name` AS product_name
	          , `price` AS product_price
	          , `brief` AS product_brief
	          , `condition` AS product_condition 
	        FROM
	          `es_product` 
	        WHERE is_delete = 0 AND `is_draft` = 0
	          ".$concatQuery."
	          ) AS main_tbl 
	        ON main_tbl.product_id = es_product_image.`product_id` 
	    WHERE `es_product_image`.`is_primary` = 1 
	      AND main_tbl.product_id = es_product_image.`product_id` 
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

	function itemKeySearch($words)
	{		 
		$totalKeywords = count($words);

        $query = "
           SELECT DISTINCT(`keywords`) FROM `es_keywords` WHERE  `keywords` LIKE ?
           ";
       
        for($i=1 ; $i < $totalKeywords; $i++){
        	$query .= " AND  keywords LIKE ?";
        }
 	
 		$query .= "LIMIT 12";
		$sth = $this->db->conn_id->prepare($query);

		foreach($words as $key => $keyword){
			$keyword = '%'.$keyword.'%';
			$key = $key+1;
			$sth->bindValue($key, $keyword , PDO::PARAM_STR);

		}
   
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
		return $row;
	}

	function addReply($data = array())
	{
		$query = $this->sqlmap->getFilenameID('product','addReply');

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':review',$data['review']);
		$sth->bindParam(':p_reviewid',$data['p_reviewid']);
		$sth->bindParam(':product_id',$data['product_id']);
		$sth->bindParam('member_id',$data['member_id']);
		$sth->execute();

	}
	
	function updateIsDelete($productid, $memberid,$is_delete){
		$query = $this->sqlmap->getFIlenameID('product', 'updateIsDelete');

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
            
            $deposit_details = array('bank_id'=>'0','acct_no'=>'','acct_name'=>'');
            $user_accounts = $this->memberpage_model->get_billing_info($memberid);
            foreach($user_accounts as $account){
                if(intval($account['id_billing_info']) === intval($billing_id)){
                    $deposit_details['bank_id'] = $account['bank_id'];
                    $deposit_details['acct_name'] = $account['bank_account_name'];
                    $deposit_details['acct_no'] = $account['bank_account_number'];
                }
            }

            if(strlen(trim($slug)) == 0 ){
                $slug = $this->createSlug($title);
                $query = $this->sqlmap->getFIlenameID('product', 'finalizeProduct');
                $sth = $this->db->conn_id->prepare($query);
                $sth->bindParam(':slug',$slug ,PDO::PARAM_STR);
            }else{
                $query = $this->sqlmap->getFIlenameID('product', 'finalizeProductKeepSlug');
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
    
    
    

	function checkifexistcategory($cat_id)
	{
		$query = $this->sqlmap->getFIlenameID('product', 'checkifexistcategory');

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':cat_id',$cat_id,PDO::PARAM_STR); 
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_COLUMN,0);
		return $row[0];
	}
	
	function getPopularitem($cat_ids,$limit)
	{	 
		$query = $this->sqlmap->getFilenameID('product','getPopularitem');
        
        $qmarks = implode(',', array_fill(0, count($cat_ids), '?'));
        $query = $query.'('.$qmarks.') ORDER BY `clickcount` DESC LIMIT ?';
        
		$sth = $this->db->conn_id->prepare($query);
        array_push($cat_ids, intval($limit));  
        foreach ($cat_ids as $k => $id)
            $sth->bindValue(($k+1), $id, PDO::PARAM_INT);   
        $sth->execute();   
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
             
		$this->explodeImagePath($rows);
              
		return $rows;
	}
        
		
	function getRecommendeditem($cat_id,$limit,$prod_id)
	{	 
		$query = $this->sqlmap->getFilenameID('product','getPopularitem');
        $query = $query.'(?) ORDER BY `clickcount` DESC LIMIT ?';
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(1,$cat_id, PDO::PARAM_INT);
		$sth->bindParam(2,$limit, PDO::PARAM_INT);
		$sth->execute();
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);	
        $this->explodeImagePath($rows);
		foreach($rows as $key=>$row){
			if(intval($row['id_product']) == intval($prod_id)){
				unset($rows[$key]);
			}
		}
		return array_values($rows);
	}
	
    function getProductById($id){
		$query = $this->sqlmap->getFilenameID('product','getProductById');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$id);
		$sth->execute();
		$rows = $sth->fetch(PDO::FETCH_ASSOC);
        /* Get actual price, apply any promo calculation */
        $rows['original_price'] = $rows['price'];
        $rows['price'] = $this->GetPromoPrice($rows['price'],$rows['startdate'],$rows['enddate'],$rows['is_promote'],$rows['promo_type']);
        /* Separate image file path and file name */
        $temp = array($rows);
        $this->explodeImagePath($temp);
        $rows = $temp[0];
        return $rows;
	}
		
	function getProductCount($down_cat){
        $qmarks = implode(',', array_fill(0, count($down_cat), '?'));
		$query = $this->sqlmap->getFilenameID('product','getProductCount');
        $query = $query.'('.$qmarks.')';
		$sth = $this->db->conn_id->prepare($query);
        $sth->execute($down_cat);
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	
	function getProductEdit($product_id, $member_id){
		$query = $this->sqlmap->getFilenameID('product','getProductEdit');

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
        $query = $this->sqlmap->getFilenameID('product','editProduct');

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
        
        $bool = $sth->execute();
        
        if(!$bool){
            $errorInfo = $sth->errorInfo();
            log_message('error', 'PDO::EDIT: 0=>'. $errorInfo[0]);
            log_message('error', 'PDO::EDIT: 1=>'. $errorInfo[1]);
            log_message('error', 'PDO::EDIT: 2=>'. $errorInfo[2]);
        }
		
		return $sth->rowCount();
    }
    
    function editProductCategory($cat_id,$product_id,$member_id){
        $query = $this->sqlmap->getFilenameID('product','editProductCategory');
        
        $sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$product_id);
        $sth->bindParam(':member_id',$member_id);
		$sth->bindParam(':cat_id',$cat_id);
        $sth->execute();

		return($sth->rowCount());
        
    }
    
    
    #Deletes product attributes in es_product_attr table, returns number of affected rows
    function deleteAttributeByProduct($product_id,$attribute_id=0)
	{
        if($attribute_id !== 0){
            $query = $this->sqlmap->getFilenameID('product','deleteProductAttributeByIDs');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':product_id',$product_id);
            $sth->bindParam(':attr_id',$attribute_id);
        }
        else{
            $query = $this->sqlmap->getFilenameID('product','deleteProductAttributes');
            $sth = $this->db->conn_id->prepare($query);
            $sth->bindParam(':product_id',$product_id);
        }
		$sth->execute();
        return $sth->rowCount(); 
	}
    
    #Deletes product image in es_product_image table, returns number of affected rows
    function deleteProductImage($product_id, $image_id)
    {
        $query = $this->sqlmap->getFilenameID('product','deleteProductImage');
        
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':product_id',$product_id);
		$sth->bindParam(':image_id',$image_id);
		$sth->execute();
        
        return $sth->rowCount();
    }

    function deleteAttrOthers($other_head_id)
    {
        $query = $this->sqlmap->getFilenameID('product','deleteOtherDetail');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':head_id',$other_head_id);
		$sth->execute();

        $query = $this->sqlmap->getFilenameID('product','deleteOtherHead');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':head_id',$other_head_id);
        $sth->execute();
    }
    

	function updateImageIsPrimary($image_id, $is_primary){
		$query = $this->sqlmap->getFilenameID('product','updateImageIsPrimary');
        
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':image_id',$image_id, PDO::PARAM_INT);
		$sth->bindParam(':is_primary',$is_primary, PDO::PARAM_INT);
		
		$sth->execute();
	}
    
	#Separates img path and img file from product_image_path
	#Result is stored back to the original array by reference
	#Arguments: $array: 1D array data from database fetch
	#           $empty: (boolean value) if true, the default path and file are set to empty strings
	function explodeImagePath(&$array=array(), $empty = false){	
		foreach($array as $key=>$row){		
			if(trim($row['product_image_path']) === ''){
				if(!$empty){
					$row['path'] = 'assets/product/default/';
					$row['file'] = 'default_product_img.jpg';
				}
				else{
					$row['path'] = '';
					$row['file'] = '';
				}
			}
			else{
				#$row['product_image_path'] = ($row['product_image_path'][0]=='.')?substr($row['product_image_path'],1,strlen($row['product_image_path'])):$row['product_image_path'];
				#$row['product_image_path'] = ($row['product_image_path'][0]=='/')?substr($row['product_image_path'],1,strlen($row['product_image_path'])):$row['product_image_path'];
				$rev_url = strrev($row['product_image_path']);
				$row['path'] = substr($row['product_image_path'],0,strlen($rev_url)-strpos($rev_url,'/'));
				$row['file'] = substr($row['product_image_path'],strlen($rev_url)-strpos($rev_url,'/'),strlen($rev_url));
			}
			#unset($row['product_image_path']);
			$array[$key] = $row;
		}
	}

    public function getProductQuantity($product_id, $verbose = false){
        if($verbose){
            $query = $this->sqlmap->getFilenameID('product','getProductQuantityVerbose');
        }
        else{
            $query = $this->sqlmap->getFilenameID('product','getProductQuantity');
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
            $query = $this->sqlmap->getFilenameID('product','getProductQuantity');
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
            //DELETE LOCK ITEMS THAT ARE 10 MINUTES IN LIFE TIME
            if(round((strtotime($lock['timenow']) - strtotime($lock['timestamp']))/60) > 10){
                $this->deleteProductItemLock($lock['id_item_lock']);
            }else{
                if(isset($data[$lock['id_product_item']])){
                    $data[$lock['id_product_item']]['quantity'] -=  $lock['lock_qty'];
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
        $query = $this->sqlmap->getFilenameID('product','getCategoriesNavigation');
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
    	$query = $this->sqlmap->getFilenameID('product','getLocation');
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
		$query = $this->sqlmap->getFilenameID('product','getPrdShippingAttr');
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
    	$query = $this->sqlmap->getFilenameID('product','storeShippingPrice');
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
		$query = $this->sqlmap->getFilenameID('product','storeProductShippingMap');
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
		$query = $this->sqlmap->getFilenameID('product','storeShippingPreferenceHead');
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
		$query = $this->sqlmap->getFilenameID('product','storeShippingPreferenceDetail');
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
		$query = $this->sqlmap->getFilenameID('product','getShippingPreferenceHead');
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
		$query = $this->sqlmap->getFilenameID('product','deleteShippingPreference');
    	$sth = $this->db->conn_id->prepare($query);
    	$sth->bindParam(':member_id', $member_id, PDO::PARAM_INT);
		$sth->bindParam(':head_id', $headId, PDO::PARAM_INT);
    	$result = $sth->execute();	
		
		return $result;
	}
	
	public function getProductItem($productId, $memberId)
	{
		$query = $this->sqlmap->getFilenameID('product','getProductItem');
    	$sth = $this->db->conn_id->prepare($query);
    	$sth->bindParam(':product_id', $productId, PDO::PARAM_INT);
    	$sth->bindParam(':member_id', $memberId, PDO::PARAM_INT);
    	$result = $sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

    	return $row;
	}
	
	public function getShippingSummary($prod_id)
	{
		$query = $this->sqlmap->getFilenameID('product', 'getShippingSummary');
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
		$query = $this->sqlmap->getFilenameID('product', 'getShippingPreference');
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
		$query = $this->sqlmap->getFilenameID('product', 'getShippingPreference');
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
		$query = $this->sqlmap->getFilenameID('product', 'getShippingIdFromShippingDetail');
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
		$query = $this->sqlmap->getFilenameID('product', 'deleteShippingDetail');
		for( $i=0; $i<count($arrProductItemId); $i++ ){
			$query .= '?,';
		}
		$query = substr($query, 0, -1);
		$query .= ' )';	
		$sth = $this->db->conn_id->prepare($query);
    	$sth->execute($arrProductItemId);
		
		// Delete Shipping Head Entries
		$query = $this->sqlmap->getFilenameID('product', 'deleteShippingHead');
		for( $i=0; $i<count($arrShippingId); $i++ ){
			$query .= '?,';
		}
		$query = substr($query, 0, -1);
		$query .= ' )';	
		$sth = $this->db->conn_id->prepare($query);
    	$sth->execute($arrShippingId);
	}
	
    /*
     * Use fulltext search to find strings in es_cat.name 
     * Returns all matched category names.
     */
    public function searchCategory($string){
        $query = $this->sqlmap->getFilenameID('product','searchCategory');
    	$sth = $this->db->conn_id->prepare($query);
    	$sth->bindParam(':sch_string', $string, PDO::PARAM_STR);
        $sth->execute();
    	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    
    /*
     * Use fulltext search to find strings in es_cat.name 
     * Returns all matched brand names.
     */
    public function searchBrand($string){
        $query = $this->sqlmap->getFilenameID('product','searchBrand');
    	$sth = $this->db->conn_id->prepare($query);
    	$sth->bindParam(':sch_string', $string, PDO::PARAM_STR);
        $sth->execute();
    	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }

    /* 
     * Return All Draft items by the user.
     * @member_id
     */
    public function getDraftItems($member_id){
    	$query = $this->sqlmap->getFilenameID('product','getDraftItems');
    	$sth = $this->db->conn_id->prepare($query);
    	$sth->bindParam(':member_id', $member_id, PDO::PARAM_INT);
    	$sth->execute();
    	$result = $sth->fetchAll(PDO::FETCH_ASSOC);

    	return $result;
    }

    public function deleteDraft($member_id,$product_id){
    	$query = $this->sqlmap->getFilenameID('product','deleteDraft');
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
        $query = $this->sqlmap->getFilenameID('product','getShipmentInformation');
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
                $data[$row['id_shipping']]['product_attribute_ids'] = array();
            }  
            array_push($data[$row['id_shipping']]['product_attribute_ids'], array('id' => $row['product_attr_id'], 'is_other' => $row['is_other']));                        
        }
        return $data;  
    }
    
	public function getCategoryBySlug($slug)
	{
		$query = "SELECT id_cat, name, description FROM es_cat WHERE slug = :slug";
    	$sth = $this->db->conn_id->prepare($query);
    	$sth->bindParam(':slug', $slug, PDO::PARAM_STR);
    	$result = $sth->execute();
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        $return  = array();
		if( count($rows) != 1 ){
			$return['id_cat'] = 0;
            $return['name'] = '';
            $return['description'] = '';
		}else{
            $return = $rows[0];
        }
		
        return $return;  
	}
    
    
    public function getHomeXML($file){
        $xml = simplexml_load_file(APPPATH . "resources/" . $file . ".xml");
        $simple = json_decode(json_encode($xml), 1);
        $home_view_data = array();
        foreach ($simple as $key => $element){
            if(isset($element['value']) &&  isset($element['type'])){   
                if($element['type'] === 'product'){
                    $productdata = $this->getProductById($element['value']);
                    if (!empty($productdata)){
                        $home_view_data[$key] = $productdata;                
                    }
                    else{
                        $home_view_data[$key] = array();
                    }
                }else if($element['type'] === 'date'){
                        $home_view_data[$key] = date('M d,Y H:i:s',strtotime($element['value']));
                }else{
                    $home_view_data[$key] = $element['value'];
                }
            }else{
                foreach ($element as $key2 => $inner_el){
                    if($inner_el['type'] === 'product'){
                        $productdata = $this->getProductById($inner_el['value']);
                        if (!empty($productdata)){
                            $home_view_data[$key][$key2] = $productdata;
                        }
                        else{
                            $home_view_data[$key][$key2] = array();
                        }
                    }else if($inner_el['type'] === 'date'){
                        $home_view_data[$key][$key2] = date('M d,Y H:i:s',strtotime($inner_el['value']));
                    }
                    else{
                        $home_view_data[$key][$key2] = $inner_el['value'];
                    }
                }
            }    
       }
       return $home_view_data;
    }

    
    public function GetPromoPrice($baseprice,$start,$end,$is_promo,$type){
        $today = strtotime( date("Y-m-d H:i:s"));
        $startdate = strtotime($start);
        $enddate = strtotime($end);
        $type = intval($type);
        switch ($type) {
            case 1 :
                if(($today < $startdate) || ($enddate > $startdate)){
                    $diffHours = 0;
                }else if($today >= $enddate){
                    $diffHours = 0.99;
                }else{
                    $diffHours = floor(($today - $startdate) / 3600);
                }
                $PromoPrice = $baseprice - (($diffHours * 0.02) * $baseprice);
                break;
            default :
                $PromoPrice = $baseprice;
                break;
        }
        return (intval($is_promo) === 1)?$PromoPrice:$baseprice;
    }
    
    
    
}
