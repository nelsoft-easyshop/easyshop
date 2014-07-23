<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class my_model extends CI_Model
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library("xmlmap");
	}	
	
	# Currently in use for generating slug for member table
	function createSlug($title, $table = 0)
    {
    	$titleLower = strtolower($title);
    	$slugGenerate = "";
		
		$query = "SELECT count(slug) AS cnt_slug FROM `es_member` WHERE slug LIKE :slug";
		
    	$bindValue = es_url_clean($titleLower);
    	$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':slug',$bindValue ,PDO::PARAM_STR);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_COLUMN,0);
        $cnt = $row[0];
        
        if( (int)$cnt === 0){
        	$slugGenerate = es_url_clean($titleLower);
        }
        
        #IDEALLY, THIS BLOCK OF CODE IS NOT NEEDED, HOWEVER THERE ARE RARE INSTANCES WHEN
        #THE SAME SLUG IS GENERATED FOR TWO ENTRIES  

		$query = "SELECT id_member as id FROM es_member WHERE slug = :slug";
        
     	$sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':slug',$slugGenerate ,PDO::PARAM_STR);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        if($row['id']>0){
            $slugGenerate = $slugGenerate.'-'.$row['id'];
        }
        return $slugGenerate;
    }
	
	function getNoSlugProduct()
	{
		$query = "SELECT id_product, TRIM(LOWER(`name`)) as `name` FROM `es_product` WHERE slug = '' ";
    	$sth = $this->db->conn_id->prepare($query);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        
		return $row;
	}
	
	function updateProductSlug($id, $slug)
	{
		$query = "UPDATE `es_product` SET slug = :slug WHERE id_product = :product_id";
    	$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':slug',$slug ,PDO::PARAM_STR);
		$sth->bindParam(':product_id',$id ,PDO::PARAM_STR);
        $result = $sth->execute();
        
		return $result;
	}
	
	function getNoSlugCategory()
	{
		$query = "SELECT id_cat, TRIM(LOWER(`name`)) as `name` FROM `es_cat` WHERE slug = '' ";
    	$sth = $this->db->conn_id->prepare($query);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        
		return $row;
	}
	
	function updateCategorySlug($id, $slug)
	{
		$query = "UPDATE `es_cat` SET slug = :slug WHERE id_cat = :cat_id";
    	$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':slug',$slug ,PDO::PARAM_STR);
		$sth->bindParam(':cat_id',$id ,PDO::PARAM_STR);
        $result = $sth->execute();
        
		return $result;
	}
	
	function getNoSlugMember()
	{
		$query = "SELECT `id_member`, TRIM(LOWER(`username`)) as username FROM es_member WHERE slug = '' ";
		$sth = $this->db->conn_id->prepare($query);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);
		
		return $row;
	}
	
	function updateMemberSlug( $id, $slug )
	{
		$query = "UPDATE `es_member` SET slug = :slug WHERE id_member = :member_id";
    	$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':slug',$slug ,PDO::PARAM_STR);
		$sth->bindParam(':member_id',$id ,PDO::PARAM_STR);
        $result = $sth->execute();
        
		return $result;
	}

}

?>