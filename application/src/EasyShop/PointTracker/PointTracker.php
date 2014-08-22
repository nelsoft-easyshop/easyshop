<?php

namespace EasyShop\PointTracker;

class PointTracker
{
	
	private $em;

	public function __construct()
	{
		$this->em = get_instance()->kernel->serviceContainer['entity_manager'];
	}

	

	public function addUserPoint($userId, $actionId)
	{

		// Check if user exists
		/*
		$user = $this->em->getRepository('EasyShop\Entities\EsPoint')
							->getUserPointData($userId);
		*/
							
		/*
		if($user === null){
			echo 'whut';
		}
		*/
		//echo $user->getM()->getUsername();
		//echo $user->getId() . '|' . $user->getPoint() . '|' . $user->getM();
		//var_dump($user);
	}

	/*

	public function getActionId($actionString)
	{
		
		return $this->em->getRepository('EasyShop\Entities\EsPointType')
								->getActionId($actionString);
		
		//var_dump($data);
	}


	public function getUserPoint($userId)
	{
		$data = $this->em->getRepository('EasyShop\Entities\EsPoint')
								->getUserPointData($userId);

		//var_dump($data);
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

	*/
}