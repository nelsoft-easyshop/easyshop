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
    
    function getAllKeywords(){
        $query = $this->sqlmap->getFilenameID('product','getAllKeyword');
        $sth = $this->db->conn_id->prepare($query);
		$sth->execute();       
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $row;
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
	

	function SearchProduct($catID, $start, $per_page, $sort, $gis, $gus, $gcon, $gloc, $gp1, $gp2, $gsubcat, $othr_att, $brnd_att, $test){ 
		

		//// MAIN CATEGORY /////////////////////////////////////////////////
		$mc = "";
		if($catID != 1){
			$mc = "AND ep.`cat_id` IN (". $catID . ") ";
		}
		//// SUBCAT ///////////////////////////////////////////////////////		
		$sc = "";			
		if($gsubcat){
			$sc = " AND ep.`cat_id` IN (" . $gsubcat . ") ";
		}
		
		//// OTHER ATTRIBUTES /////////////////////////////////////////////
		$oa = "";
		if(!empty($othr_att)){
			$fin_attrib = "";
			$or = "";
			foreach($othr_att as $rows => $values){
				if($rows > 0){
					$or = " OR ";
				}
				$fin_attrib .= $or . $values['q'];
			}

			$oa = " AND ep.`id_product` IN (SELECT epa.`product_id` FROM `es_attr` ea
				LEFT JOIN `es_product_attr` epa ON ea.`id_attr` = epa.`attr_id`
				WHERE epa.`product_id` IS NOT NULL 
					AND ea.`cat_id` IN (". $catID .")
					AND (". $fin_attrib .")
				GROUP BY epa.`product_id`
				HAVING COUNT(*) = :sc )";	

		}

		//// BRAND //////////////////////////////////////////////////////
		$ba = "";
		if(!empty($brnd_att)){
			$bpdo = "";
			foreach($brnd_att as $rows => $values){
				$bpdo .= ":bn" . $rows . ", ";
			}

			$ba = " AND eb.`name` IN (". substr($bpdo,0,strlen($bpdo)-2) . ") ";
			 
		}		
		
		//// SORT ///////////////////////////////////////////////////////
		if($sort){
			switch($sort){
				case "hot": $colsort = "ep.is_hot"; break;
				case "new": $colsort = "ep.is_new"; break;
				case "popular": $colsort = "ep.clickcount"; break;
				case "con": $colsort = "ep.condition"; break;
				default: $colsort = "ep.clickcount";							
			}
			unset($sort);
		}else{
			$colsort = "ep.`id_product`";
		}		

		//// KEYWORD //////////////////////////////////////////////////////
		$is = "";
		if(strlen($gis) > 0){
			$is = " AND MATCH(ep.`name`,keywords) AGAINST(CONCAT(:gis,'*') IN BOOLEAN MODE) ";
		}
		
		//// USERNAME /////////////////////////////////////////////////////
		$us = "";
		if(strlen($gus) > 0){
			$us = " AND em.username = :gus";
		}			

		//// CONDITION ////////////////////////////////////////////////////
		$con = "";
		if(strlen($gcon) > 0){
			$con = " AND ep.`condition` = :gcon ";
		}
		
		//// LOCATION /////////////////////////////////////////////////////		
		$loc = "";
		if($gloc){
			$loc = " AND ep.`id_product` IN (SELECT `product_id` FROM `es_product_shipping_head` WHERE `location_id` = :gloc) ";
		}		
	
		//// PRICE ///////////////////////////////////////////////////////	
		$gp = "";
		if(strlen($gp1) > 0 && strlen($gp2) > 0){
			$gp = " AND ep.`price` BETWEEN :gp1 AND :gp2 ";
		}	
	
		################################################################
		
//		echo "<br><br>";
//		echo $ba;
//		echo "<br><br>";		
						
		$start = (int)$start;
		$per_page = (int)$per_page;
			
		$query = "SELECT em.`username`, ep.`slug`, ep.`id_product` AS 'product_id', ep.`brand_id` AS 'brand_id', 
			ep.`cat_id`, ep.`name` AS 'product_name', 
			ep.`price` AS 'product_price', ep.`brief` AS 'product_brief', ep.`condition` AS 'product_condition',
			epi.`product_image_path`,
			eb.`name` AS 'product_brand'
			FROM `es_product` ep
			LEFT JOIN `es_product_image` epi ON ep.`id_product` = epi.`product_id` AND epi.`is_primary` = 1
			LEFT JOIN `es_brand` eb ON ep.`brand_id` = eb.`id_brand` 
			LEFT JOIN `es_member` em ON ep.`member_id` = em.`id_member` 
			WHERE ep.`is_draft` = 0 AND ep.`is_delete` = 0 ". $mc ."   
			". $oa . " ". $sc ." ". $loc . " " . $is ." " . $us . " ". $con ." ". $gp ." " . $ba . "   
			ORDER BY :colsort DESC 
			LIMIT :start, :per_page ";
	
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':start',$start,PDO::PARAM_INT);
		$sth->bindParam(':per_page',$per_page,PDO::PARAM_INT);
		$sth->bindParam(':colsort',$colsort,PDO::PARAM_INT);
		if(!empty($othr_att)){
			$ctr = 0;
			foreach($othr_att as $rows => $values){
				$snf = ':sn'.$values['c'];
				$snv = $values['n'];
				$sth->bindValue($snf,$snv,PDO::PARAM_STR);
				
				$saf = ':sa'.$values['c'];
				$sav = $values['a'];				
				$sth->bindValue($saf,$sav,PDO::PARAM_STR);
				
				$ctr = $ctr + 1;
			}

			$sth->bindParam(':sc',$ctr ,PDO::PARAM_STR);
		}
		if(!empty($brnd_att)){
			foreach($brnd_att as $rows => $values){
				$bnf = ':bn'.$rows;
				$bnv = $values; 
	 			$sth->bindValue($bnf,$bnv,PDO::PARAM_STR); 
			}
		}	
		(strlen($gis)>0)? $sth->bindParam(':gis',$gis,PDO::PARAM_STR) : null;
		(strlen($gus)>0)? $sth->bindParam(':gus',$gus,PDO::PARAM_STR) : null;
		(strlen($gcon)>0)? $sth->bindParam(':gcon',$gcon,PDO::PARAM_STR) : null;
		(strlen($gloc)>0)? $sth->bindParam(':gloc',$gloc,PDO::PARAM_INT) : null;
		if(strlen($gp1)>0 && strlen($gp2)>0){
			$sth->bindParam(':gp1',$gp1,PDO::PARAM_INT);
			$sth->bindParam(':gp2',$gp2,PDO::PARAM_INT);		
		}
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		if($test == "ok"){print_r($query);}	
	
		return $row;			
	}
	
	function ProductCount($catID, $gis, $gus, $gcon, $gloc, $gp1, $gp2, $gsubcat, $othr_att, $brnd_att){

		//// MAIN CATEGORY /////////////////////////////////////////////////
		$mc = "";
		if($catID != 1){
			$mc = "AND ep.`cat_id` IN (". $catID . ") ";
		}
		//// SUBCAT ///////////////////////////////////////////////////////		
		$sc = "";			
		if($gsubcat){
			$sc = " AND ep.`cat_id` IN (" . $gsubcat . ") ";
		}
		
		//// OTHER ATTRIBUTES /////////////////////////////////////////////
		$oa = "";
		if(!empty($othr_att)){
			$fin_attrib = "";
			$or = "";
			foreach($othr_att as $rows => $values){
				if($rows > 0){
					$or = " OR ";
				}
				$fin_attrib .= $or . $values['q'];
			}

			$oa = " AND ep.`id_product` IN (SELECT epa.`product_id` FROM `es_attr` ea
				LEFT JOIN `es_product_attr` epa ON ea.`id_attr` = epa.`attr_id`
				WHERE epa.`product_id` IS NOT NULL 
					AND ea.`cat_id` IN (". $catID .")
					AND (". $fin_attrib .")
				GROUP BY epa.`product_id`
				HAVING COUNT(*) = :sc )";	

		}

		//// BRAND //////////////////////////////////////////////////////
		$ba = "";
		if(!empty($brnd_att)){
			$bpdo = "";
			foreach($brnd_att as $rows => $values){
				$bpdo .= ":bn" . $rows . ", ";
			}

			$ba = " AND eb.`name` IN (". substr($bpdo,0,strlen($bpdo)-2) . ") ";
			 
		}		

		//// KEYWORD //////////////////////////////////////////////////////
		$is = "";
		if(strlen($gis) > 0){
			$is = " AND MATCH(ep.`name`,keywords) AGAINST(CONCAT(:gis,'*') IN BOOLEAN MODE) ";
		}
		
		//// USERNAME /////////////////////////////////////////////////////
		$us = "";
		if(strlen($gus) > 0){
			$us = " AND em.`username` = :gus ";
		}			

		//// CONDITION ////////////////////////////////////////////////////
		$con = "";
		if(strlen($gcon) > 0){
			$con = " AND ep.`condition` = :gcon ";
		}
		
		//// LOCATION /////////////////////////////////////////////////////		
		$loc = "";
		if($gloc){
			$loc = " AND ep.`id_product` IN (SELECT `product_id` FROM `es_product_shipping_head` WHERE `location_id` = :gloc) ";
		}		
	
		//// PRICE ///////////////////////////////////////////////////////	
		$gp = "";
		if(strlen($gp1) > 0 && strlen($gp2) > 0){
			$gp = " AND ep.`price` BETWEEN :gp1 AND :gp2 ";
		}	
	
		################################################################
	
		$query = "SELECT count(ep.`id_product`) AS ctr
			FROM `es_product` ep
			LEFT JOIN `es_product_image` epi ON ep.`id_product` = epi.`product_id` AND epi.`is_primary` = 1
			LEFT JOIN `es_brand` eb ON ep.`brand_id` = eb.`id_brand` 
			LEFT JOIN `es_member` em ON ep.`member_id` = em.`id_member` 
			WHERE ep.`is_draft` = 0 AND ep.`is_delete` = 0 ". $mc ."   
			". $oa . " ". $sc ." ". $loc . " " . $is ." " . $us . " ". $con ." ". $gp ." " . $ba ;
			
		$sth = $this->db->conn_id->prepare($query);
		if(!empty($othr_att)){
			$ctr = 0;
			foreach($othr_att as $rows => $values){
				$snf = ':sn'.$values['c'];
				$snv = $values['n'];
				$sth->bindValue($snf,$snv,PDO::PARAM_STR);
				
				$saf = ':sa'.$values['c'];
				$sav = $values['a'];				
				$sth->bindValue($saf,$sav,PDO::PARAM_STR);
				
				$ctr = $ctr + 1;
			}

			$sth->bindParam(':sc',$ctr ,PDO::PARAM_STR);
		}
		if(!empty($brnd_att)){
			foreach($brnd_att as $rows => $values){
				$bnf = ':bn'.$rows;
				$bnv = $values; 
	 			$sth->bindValue($bnf,$bnv,PDO::PARAM_STR); 
			}
		}	
		(strlen($gis)>0)? $sth->bindParam(':gis',$gis,PDO::PARAM_STR) : null;
		(strlen($gus)>0)? $sth->bindParam(':gus',$gus,PDO::PARAM_STR) : null;
		(strlen($gcon)>0)? $sth->bindParam(':gcon',$gcon,PDO::PARAM_STR) : null;
		(strlen($gloc)>0)? $sth->bindParam(':gloc',$gloc,PDO::PARAM_INT) : null;
		if(strlen($gp1)>0 && strlen($gp2)>0){
			$sth->bindParam(':gp1',$gp1,PDO::PARAM_INT);
			$sth->bindParam(':gp2',$gp2,PDO::PARAM_INT);		
		}
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
	
		return $row;	
	}
}