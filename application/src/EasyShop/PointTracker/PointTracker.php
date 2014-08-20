<?php

namespace EasyShop\PointTracker;

//$this->CI->load->model("register_model");
//var_dump($this->CI->register_model->get_member_by_username('master'));
//var_dump($this->CI);


class PointTracker
{
	
	private $CI;

	public function __construct()
	{
		$this->CI = get_instance();
	}

	public function addPoint($userId, $typeId)
	{
		// get point equiv of submitted typeid
		$query = "SELECT point FROM es_point_type WHERE id = :id LIMIT 1;";
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$typeId);
		$sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);

		print_r($result);
	}



	public function addPointType($typeName, $typePoint, $em)
	{
		$this->CI->load->model("pointtype_model");
		//echo $this->CI->pointtype_model->add_type($typeName, $typePoint, $em);
	}



	public function getPoint($userId)
	{
		// query db for userId's current points
		$query = "SELECT point FROM es_point WHERE id = :id LIMIT 1;";
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':id',$userId);
		$sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);

		print_r($result);
	}

	public function getPointHistory()
	{
		$query = "SELECT * FROM es_point_history;";
		$sth = $this->db->conn_id->prepare($query);
		$sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC); 
	}

	public function getActionId($actionString)
	{
		// query db for actionid here, if not found, return a def value of 0
		$query = "SELECT id FROM es_point_type WHERE name = :name LIMIT 1;";
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':name',$actionString);
		$sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);

		print_r($result);
	}
}