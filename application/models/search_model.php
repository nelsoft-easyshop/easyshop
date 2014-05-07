<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class search_model extends CI_Model
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library("sqlmap");
	}
	
	# get all down level category on selected category from database
	# parent -> child -> child
	function getDownLevelNode($id) 
	{
		$query = $this->sqlmap->getFilenameID('product', 'selectDownLevel');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':parent_id', $id);
		$sth->execute();
		$row = $sth->fetchAll();
		return $row;
	}
	
	#get all parent category from selected id.
	# child -> parent -> parent
	function getParentId($id) 
	{
		$query = $this->sqlmap->getFilenameID('product','getParent');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$id);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $row;
	}
	
	function getProductID($id)
	{
		$query = "SELECT ep.`id_product` AS 'product_id', ep.`brand_id` FROM `es_product` ep 
			WHERE ep.`cat_id` IN (". $id .") AND ep.`is_draft` = 0 AND ep.`is_delete` = 0";
        $sth = $this->db->conn_id->prepare($query);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $row;			
	}
	
	
	# get all attributes from all parents from to the last selected category
	function getAttributesByParent($parents) 
	{
		if(is_array($parents)){
			$value = implode(',',$parents);
		}else{
			$value = $parents;
		}
		
		$query = "SELECT DISTINCT
  			ea.name AS cat_name, ea.id_attr, ea.attr_lookuplist_id, ed.name AS input_type, eal.name AS input_name 
			FROM es_attr ea
			LEFT JOIN es_datatype ed ON ea.datatype_id = ed.id_datatype 
			LEFT JOIN es_attr_lookuplist eal ON ea.attr_lookuplist_id = eal.id_attr_lookuplist 
			WHERE ea.cat_id IN (" .$value .")
  				AND ed.name IN ('CHECKBOX', 'RADIO', 'SELECT')
  				GROUP BY ea.name
				ORDER BY ea.name ASC ";

		$sth = $this->db->conn_id->prepare($query);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $row;
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
	
	function getBrandName($var, $toggle)
	{
		if($toggle == 'id'){
			if(is_array($var)){
				$value = implode(',',$var);
			}else{
				$value = $var;
			}
			
			$condition = "eb.`id_brand` IN (". $value .")";
		}else{
			$condition = "eb.`name` LIKE '%". $var ."%'";		
		}
		$query = "SELECT DISTINCT eb.`name` FROM `es_brand` eb WHERE " . $condition;
			
		$sth = $this->db->conn_id->prepare($query);
		$sth->execute();
		
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $row;				
	}	
	
	function getAttributesWithParam($catID,$pid_values)
	{
		if(is_array($catID)){
			$cid = implode(',', $catID);
		}else{
			$cid = $catID;
		}
		
		if(!empty($pid_values)){
			$cdatas = implode(',', $pid_values);
		}else{
			$cdatas = "";
		}
		
		$query = "SELECT DISTINCT ea.`name` FROM `es_product_attr` epa
			LEFT JOIN `es_attr` ea ON epa.`attr_id` = ea.`id_attr`
			LEFT JOIN `es_product` ep ON epa.`product_id` = ep.`id_product`
			LEFT JOIN es_datatype ed ON ea.datatype_id = ed.id_datatype 			
			WHERE ep.`cat_id` IN (". $cid .")
			AND ed.name IN ('CHECKBOX', 'RADIO', 'SELECT') 
			AND epa.`product_id` IN (". $cdatas .") ORDER BY ea.`name`";
		$sth = $this->db->conn_id->prepare($query); 
		$sth->execute();

		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $row;
	} /// end
	
	function getAttributesWithParamAndName($catID,$pid_values,$name)
	{
		if(is_array($catID)){
			$cid = implode(',', $catID);
		}else{
			$cid = $catID;
		}
		
		if(!empty($pid_values)){
			$cdatas = implode(',', $pid_values);
		}else{
			$cdatas = "";
		}	
		
		$query = "SELECT DISTINCT epa.`attr_value` FROM `es_product_attr` epa
			LEFT JOIN `es_attr` ea ON epa.`attr_id` = ea.`id_attr`
			LEFT JOIN `es_product` ep ON epa.`product_id` = ep.`id_product`	
			LEFT JOIN es_datatype ed ON ea.datatype_id = ed.id_datatype 		
			WHERE ep.cat_id IN (". $cid .")
			AND ed.name IN ('CHECKBOX', 'RADIO', 'SELECT') 
			AND product_id IN (". $cdatas .") 
			AND ea.`name` = :name ORDER BY ea.`name` ";
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':name',$name);
		$sth->execute();

		$row = $sth->fetchAll(PDO::FETCH_COLUMN, 0);

		return $row;
	} /// end
	
	function getFirstLevelNode($is_main = false, $is_alpha = false) # get all main/parent/first level category from database
	{
        if(($is_main)&&(!$is_alpha)){
            $query = $this->sqlmap->getFilenameID('product', 'selectFirstLevelIsMain');
        }
        else if((!$is_main)&&(!$is_alpha)){
            $query = $this->sqlmap->getFilenameID('product', 'selectFirstLevel');
        }
        else if(($is_main)&&($is_alpha)){
            $query = $this->sqlmap->getFilenameID('product', 'selectFirstLevelIsMainAlpha');
        }
        else if((!$is_main)&&($is_alpha)){
            $query = $this->sqlmap->getFilenameID('product', 'selectFirstLevelAlpha');
        }
		
		$sth = $this->db->conn_id->prepare($query);
		$sth->execute();
		$row = $sth->fetchAll(); 

		return $row;
	}	
		
	public function SearchProduct($cat, $start, $per_page, $colsort, $is, $con, $gp, $attr_brand, $QAtt, $sc, $loc, $test){ 
	
	$start = (int)$start;
	$per_page = (int)$per_page;		

	$query = "SELECT ep.`slug`, ep.`id_product` AS 'product_id', ep.`brand_id` AS 'brand_id', 
		ep.`cat_id`, ep.`name` AS 'product_name', 
		ep.`price` AS 'product_price', ep.`brief` AS 'product_brief', ep.`condition` AS 'product_condition',
		epi.`product_image_path`,
		eb.`name` AS 'product_brand'
		FROM `es_product` ep
		LEFT JOIN `es_product_image` epi ON ep.`id_product` = epi.`product_id` AND epi.`is_primary` = 1
		LEFT JOIN `es_brand` eb ON ep.`brand_id` = eb.`id_brand`
		WHERE ep.`is_draft` = 0 AND ep.`is_delete` = 0 ". $cat ."   
		". $QAtt . " ". $sc ." ". $loc . " " . $is ." ". $con ." ". $gp ." ". $attr_brand ."  
		ORDER BY ". $colsort ." DESC 
		LIMIT :start, :per_page ";
	$sth = $this->db->conn_id->prepare($query);
	$sth->bindParam(':start',$start,PDO::PARAM_INT);
	$sth->bindParam(':per_page',$per_page,PDO::PARAM_INT);
	$sth->execute();
	$row = $sth->fetchAll(PDO::FETCH_ASSOC);

	if($test == "okok"){print_r($query); exit;}
	if($test == "ok"){print_r($query);}	

	return $row;			
	}					
	
}