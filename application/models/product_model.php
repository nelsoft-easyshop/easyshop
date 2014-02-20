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

		return explode(',', $row[0]);	
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
		ORDER BY cat_name ASC ";

		$sth = $this->db->conn_id->prepare($query);
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

	function getProduct($id) 
	{
		$query = $this->sqlmap->getFilenameID('product', 'getProduct');

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$id);
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);
        
        if((intval($row['o_success']) !== 0)&&(strlen(trim($row['userpic']))===0))
            $row['userpic'] = 'assets/user/default';
		
		return $row;
	}



	function getProductAttributes($id, $key = 'ALL') # getting the product attirbute using product ID
	{	
		$query = $this->sqlmap->getFilenameID('product', 'getProductAttributes');

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$id);
		$sth->execute();
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);	
		$data = array();
		
		foreach($rows as $row){			
			if((!array_key_exists($row['name'], $data))&&(($key === 'NAME')||($key === 'ALL')))
				$data[$row['name']] = array();
			if((!array_key_exists($row['name_id'], $data))&&(($key === 'ID')||($key === 'ALL')))
				$data[$row['name_id']] = array();
				
			$temp = array(0=>$row);
			$this->explodeImagePath($temp,true);
			$row = $temp[0];
			
			if($key === 'NAME')
				array_push($data[$row['name']],array('value' => $row['attr_value'], 'value_id' => $row['attr_value_id'],  'price'=>$row['attr_price'],'img_path'=>$row['path'], 'img_file'=>$row['file'], 'type'=>$row['type'], 'img_id' => $row['img_id']  ));
			else if($key === 'ID')
				array_push($data[$row['name_id']],array('value' => $row['attr_value'], 'value_id' => $row['attr_value_id'],  'price'=>$row['attr_price'],'img_path'=>$row['path'], 'img_file'=>$row['file'], 'type'=>$row['type'], 'img_id' => $row['img_id']   ));
			else{
				array_push($data[$row['name']],array('value' => $row['attr_value'], 'value_id' => $row['attr_value_id'],  'price'=>$row['attr_price'],'img_path'=>$row['path'], 'img_file'=>$row['file'], 'type'=>$row['type'], 'img_id' => $row['img_id']   ));
				array_push($data[$row['name_id']],array('value' => $row['attr_value'], 'value_id' => $row['attr_value_id'],  'price'=>$row['attr_price'],'img_path'=>$row['path'], 'img_file'=>$row['file'], 'type'=>$row['type'], 'img_id' => $row['img_id']   ));
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
        
	function getProductsByCategory($cat_id)
	{
		$query = $this->sqlmap->getFilenameID('product', 'getProductsByCategory');
		
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':cat_id',$cat_id);
		$sth->execute();
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		$this->explodeImagePath($rows);		
		return $rows;
	}
	
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

	function addNewProduct($product_title,$sku,$product_brief,$product_description,$keyword,$brand_id,$cat_id,$style_id,$member_id,$product_price,$product_condition)
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
		$sth->bindParam(':condition',$product_condition);
		$sth->execute();
        
		return $this->db->conn_id->lastInsertId('id_product');
	}

	function addNewAttributeByProduct($product_id,$attribute_id,$value,$price)
	{
		# this function for adding new attribute of the product to es_product_attr table.
		$query = $this->sqlmap->getFilenameID('product','addNewAttribute');

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':product_id',$product_id);
		$sth->bindParam(':attr_id',$attribute_id);
		$sth->bindParam(':attr_value',$value);
		$sth->bindParam(':attr_price',$price);
		$sth->execute();

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
		$sth->bindParam(':product_id',$product_id);
		$sth->bindParam(':qty',$qty);	
		$sth->execute();
		return $this->db->conn_id->lastInsertId('id_product_item');
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

	function selectProductAttributeOther($other_value,$product_id)
	{
		$query = $this->sqlmap->getFilenameID('product','selectProductAttributeOther');
		$sth = $this->db->conn_id->prepare($query);
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

	function getProductByCategoryIdWithDistinct($id,$condition_price_string,$string,$cnt,$start,$per_page,$catlist_down,$item_brand_string_2)
	{
		$start = (int)$start;
	 	$per_page = (int)$per_page;
		 
			 $query = " 
			SELECT mother_tbl.*,`es_brand`.`name` AS product_brand
			FROM `es_brand`
			LEFT JOIN (SELECT 
			  main_tbl.*
			  , es_product_image.product_image_path 
			FROM
			  `es_product_image` 
			  LEFT JOIN 
			    (SELECT 
			      `product_id`
			      , `brand_id` 
			      , `name` AS product_name
			      , `price` AS product_price
			      , `brief` AS product_brief
			      , `condition` AS product_condition 
			    FROM
			      `es_product` 
			      INNER JOIN 
			        (SELECT 
			          product_id 
			        FROM
			          (SELECT 
			            a.product_id
			            , a.attr_value
			            , b.name 
			          FROM
			            `es_product_attr` a
			            , `es_attr` b
			            , `es_product` c 
			          WHERE a.`attr_id` = b.`id_attr` 
			            AND a.`product_id` = c.`id_product` 
			            AND c.is_delete = 0 
			            AND c.`cat_id` IN (".$catlist_down.") ".$condition_price_string." ) AS sub_main_tbl 
			        WHERE ".$string." 
			        GROUP BY product_id 
			        HAVING COUNT(*) = :cnt) AS product_id_table 
			        ON es_product.`id_product` = product_id_table.product_id) AS main_tbl 
			    ON main_tbl.product_id = es_product_image.`product_id` 
			WHERE `es_product_image`.`is_primary` = 1 
			  AND main_tbl.product_id = es_product_image.`product_id` ) AS mother_tbl 
			ON mother_tbl.brand_id = `es_brand`.`id_brand` 
			WHERE mother_tbl.brand_id = `es_brand`.`id_brand` ".$item_brand_string_2."
			ORDER BY product_name 
			LIMIT :start, :per_page
			 ";	
		   
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':cnt',$cnt,PDO::PARAM_INT);  
		$sth->bindParam(':start',$start,PDO::PARAM_INT);
		$sth->bindParam(':per_page',$per_page,PDO::PARAM_INT);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $row;
	}

	function selectAllProductWithCategory($id_cat,$condition_price_string,$start,$per_page,$catlist_down)
	{
	
		$start = (int)$start;
	 	$per_page = (int)$per_page;		  
	 	$query ="
	 	     SELECT 
          a.`id_product` AS `product_id`
          , a.`name` AS product_name
          , a.`price` AS product_price
          , a.`brief` AS product_brief
          , a.`condition` AS product_condition
          , b.`product_image_path`
          , c.`name` AS product_brand 
          , a.`brand_id`
        FROM
          `es_product` a
          , `es_product_image` b 
          ,`es_brand` c
        WHERE a.`id_product` = b.`product_id` 
          AND b.`is_primary` = 1 
          AND a.`brand_id` = c.`id_brand`
          AND `cat_id` IN (".$catlist_down.")
		  AND a.`is_delete` = 0 
		  ".$condition_price_string." 
        LIMIT :start, :per_page
	 	"; 


		$sth = $this->db->conn_id->prepare($query);  
		$sth->bindParam(':start',$start,PDO::PARAM_INT);
		$sth->bindParam(':per_page',$per_page,PDO::PARAM_INT);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $row;
	}

	function getProductInCategoryAndUnder($category,$usable_string,$items,$start,$per_page,$string_sort)
	{
		$start = (int)$start;
	 	$per_page = (int)$per_page;
		 
		$query = "
		SELECT 
		  a.`id_product` AS product_id
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
		  AND b.`is_primary` = 1 
		  AND a.`brand_id` = c.`id_brand`
		  ".$usable_string." 
		  AND a.`cat_id` IN (".$items.") 
		   ". $string_sort ."   
		  LIMIT :start, :per_page
		";   

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':start',$start,PDO::PARAM_INT); 
		$sth->bindParam(':per_page',$per_page,PDO::PARAM_INT);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $row;
	}

	function getAttributesWithParam($id,$datas)
	{
		$query = '
		SELECT DISTINCT 
		  (b.name) 
		FROM
		  `es_product_attr` a
		  , `es_attr` b
		  , `es_product` c 
		WHERE a.`attr_id` = b.`id_attr` 
		  AND c.`cat_id` = :cat_id 
		  AND product_id IN('. implode(',', $datas) .') ORDER BY NAME';
		
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':cat_id',$id);  
		$sth->execute();

		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $row;
	}	

	function getAttributesWithParamAndName($id,$datas,$name)
	{
	
		$query = '
		SELECT 
		attr_value 
		FROM
		(SELECT DISTINCT 
			(b.name)
			, a.`attr_value` 
			FROM
			`es_product_attr` a
			, `es_attr` b
			, `es_product` c 
			WHERE a.`attr_id` = b.`id_attr` 
			AND c.`cat_id` = :cat_id 
			AND product_id IN ('. implode(', ', $datas) .') 
			ORDER BY NAME) AS new_table 
		WHERE NAME = "'.$name.'" ';
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':cat_id',$id);  
		$sth->execute();

		$row = $sth->fetchAll(PDO::FETCH_COLUMN, 0);

		return $row;
	}

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
		print_r($sth->errorInfo());
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

	function getFirstLevelNodeAlphabetical($is_main = false) # get all main/parent/first level category from database
	{
        if($is_main){
            $query = $this->sqlmap->getFilenameID('product', 'selectFirstLevelIsMain');
        }
        else{
            $query = $this->sqlmap->getFilenameID('product', 'selectFirstLevelAlphabetical');
        }
		
		$sth = $this->db->conn_id->prepare($query);
		$sth->execute();
		$row = $sth->fetchAll(); 

		return $row;
	}

	function itemSearchNoCategory($words,$start,$per_page)
	{
		$start = (int)$start;
	 	$per_page = (int)$per_page;
	

		$query = $this->sqlmap->getFilenameID('product','itemSearchNoCategory');
		$sth = $this->db->conn_id->prepare($query);
		
		$sth->bindParam(':words',$words,PDO::PARAM_STR);
		$sth->bindParam(':start',$start,PDO::PARAM_INT);
		$sth->bindParam(':per_page',$per_page,PDO::PARAM_INT);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $row;
	}

 

	function getCatItemsWithImage($cat_id)
	{	

		$query = $this->sqlmap->getFilenameID('product','getCatItemsWithImage');

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':cat_id',$cat_id);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $row;

	}

	function itemKeySearch($words)
	{
		$query = $this->sqlmap->getFilenameID('product','itemKeySearch');

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':words',$words);
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
			//print_r($sth->errorInfo());
	}
	
	#Set is_delete of es_product to 1
	function updateIsDelete($productid, $memberid,$is_delete){
		$query = $this->sqlmap->getFIlenameID('product', 'updateIsDelete');

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':productid',$productid,PDO::PARAM_INT);
		$sth->bindParam(':memberid',$memberid,PDO::PARAM_INT);
        $sth->bindParam(':is_delete', $is_delete,PDO::PARAM_INT);

		$sth->execute();
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
	
    function getProduct_withImage($id){
		$query = $this->sqlmap->getFilenameID('product','getProduct_withImage');

		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$id);
		$sth->execute();
		$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
   
        $this->explodeImagePath($rows);
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
        
		$sth->execute();
		
		return $sth->rowCount();
    }
    
    #Deletes product attributes in es_product_attr table, returns number of affected rows
    function deleteAttributeByProduct($product_id,$attribute_id)
	{
		$query = $this->sqlmap->getFilenameID('product','deleteAttribute');
        
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':product_id',$product_id);
		$sth->bindParam(':attr_id',$attribute_id);
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

        function addCategory($id,$name,$desc,$keyword,$sort_order,$is_main){
            
            $this->check_sort_order($id,$sort_order);
            $query = $this->sqlmap->getFilenameID('product','addCategory');
            
            $sth = $this->db->conn_id->prepare($query);
            
            $sth->bindParam(':parent_id',$id, PDO::PARAM_INT);
            $sth->bindParam(':name',$name);
            $sth->bindParam(':description',$desc);
            $sth->bindParam(':keywords',$keyword);
            $sth->bindParam(':sort_order',$sort_order, PDO::PARAM_INT);
            $sth->bindParam(':is_main',$is_main, PDO::PARAM_INT);
            $sth->execute();
            $data = $this->lastInsertData("es_cat",$id." AND id_cat > 1 ORDER BY sort_order ASC","parent_id");
            
            return $data;
            
        }
        
        function lastInsertData($tbl_name, $id, $field) {
            $query = "SELECT * FROM $tbl_name WHERE $field = $id ";
            $sth = $this->db->conn_id->prepare($query);
            $sth->execute();

            $row = $sth->fetchAll(PDO::FETCH_ASSOC);

            return $row;
        }
        
        function check_sort_order($id,$sort_order){
            $query = "SELECT * FROM es_cat WHERE parent_id = $id AND id_cat > 1 AND `sort_order` >= $sort_order ORDER BY sort_order ASC;";
            $sth = $this->db->conn_id->prepare($query);
            $sth->execute();
            $row = $sth->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($row)) : //sort_order exists
                for($x=0;$x < sizeof($row);$x++) :
                    $new_sort = $row[$x]['sort_order'] + 1;
                    $query = "UPDATE `es_cat` SET `sort_order` = {$new_sort} WHERE `id_cat` = {$row[$x]['id_cat']};";
                    $sth = $this->db->conn_id->prepare($query);
                    $sth->execute();
                endfor;
            endif;
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
            }
            array_push($data[$row['id_product_item']]['product_attribute_ids'], $row['product_attr_id']);
            if($verbose){
                array_push($data[$row['id_product_item']]['attr_lookuplist_item_id'], $row['attr_lookuplist_item_id']);
            }
        }
        
        return $data;
    }
    
    public function deleteProductQuantityCombination($product_id){
        $query = "SELECT id_product_item FROM es_product_item WHERE product_id = :product_id";
        $sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':product_id',$product_id, PDO::PARAM_INT);
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
        }
        
        $query = "DELETE FROM es_product_item WHERE product_id = :product_id";
        $sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':product_id',$product_id, PDO::PARAM_INT);
		$sth->execute();

    }
    
    public function getCatFast(){
        $query = $this->sqlmap->getFilenameID('product','getCatFast');
		$sth = $this->db->conn_id->prepare($query);
		$sth->execute();
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
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

        foreach($row as $r){
        	$data[$r['product_item_id']][] = $r['attr_value'];
        }

        return $data;
	}

	/**
    *	Store Shipping Price in `es_shipping_price`
    *	Table contains -> Location ID vs Price
    */
    public function storeShippingPrice ($locationKey, $price)
    {
    	$query = $this->sqlmap->getFilenameID('product','storeShippingPrice');
    	$sth = $this->db->conn_id->prepare($query);
    	$sth->bindParam(':location_id', $locationKey, PDO::PARAM_INT);
    	$sth->bindParam(':price', $price, PDO::PARAM_INT);
    	$sth->execute();

    	return $this->db->conn_id->lastInsertId('id_shipping');
    }

    /**
    *	Store Product Shipping Mapping in `es_product_shipping_map`
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
 
}
