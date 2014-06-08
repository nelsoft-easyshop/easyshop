<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
    
class search_model extends CI_Model
{ 
    function __construct() 
	{
		parent::__construct();  
        $this->load->helper('product');
        $this->load->library("xmlmap");
	}


	function advance_search($catID, $start, $per_page, $sort, $gis, $gus, $gcon, $gloc, $gp1, $gp2, $gsubcat, $othr_att, $brnd_att){ 
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
		
		$start = intval($start);
		$per_page = intval($per_page);
			
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
		$products = $sth->fetchAll(PDO::FETCH_ASSOC);
        explodeImagePath($products);
		return $products;			
	}
    

    function getAllKeywords(){
        $query = $this->xmlmap->getFilenameID('sql/search','getAllKeyword');
        $sth = $this->db->conn_id->prepare($query);
		$sth->execute();       
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }

	
    /*
     * Use fulltext search to find strings in es_cat.name 
     * Returns all matched category names.
     */
    public function searchCategory($string){
        $query = $this->xmlmap->getFilenameID('sql/search','searchCategory');
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
        $query = $this->xmlmap->getFilenameID('sql/search','searchBrand');
    	$sth = $this->db->conn_id->prepare($query);
    	$sth->bindParam(':sch_string', $string, PDO::PARAM_STR);
        $sth->execute();
    	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }
    
    
	public function itemKeySearch($words)
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

    
    public function itemSearchNoCategory($words,$start,$per_page)
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
    
    public function insertSearch($value)
	{
		$query = "INSERT INTO es_keywords_temp (keywords) VALUES(:value)"; 
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':value',$value,PDO::PARAM_STR);
        $sth->execute();
       
        $row = $sth->fetch(PDO::FETCH_ASSOC);
  
        return $row;
	}	
    
}
