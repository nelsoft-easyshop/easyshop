<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class my_model extends CI_Model
{
	function __construct() 
	{
		parent::__construct();
		$this->load->library("sqlmap");
	}	
	
	function getNoSlugProduct()
	{
		$query = "SELECT id_product, TRIM(LOWER(`name`)) as `name` FROM `es_product` WHERE slug = '' ";
    	$sth = $this->db->conn_id->prepare($query);
        $sth->execute();
        $row = $sth->fetchAll(PDO::FETCH_ASSOC);
        
		return $row;
	}
	
	function updateSlug($id, $slug)
	{
		$query = "UPDATE `es_product` SET slug = :slug WHERE id_product = :product_id";
    	$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':slug',$slug ,PDO::PARAM_STR);
		$sth->bindParam(':product_id',$id ,PDO::PARAM_STR);
        $result = $sth->execute();
        
		return $result;
	}
	
	
}

?>