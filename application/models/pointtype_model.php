<?php	
	require_once('../application/src/EasyShop/Entities/PointType.php');
	use EasyShop\Entities\PointType;

	class PointType_Model extends CI_Model
	{
		private $CI;
		public function __construct()
		{
			parent::__construct();
			$this->CI = get_instance();
		}

		public function add_type($typeName, $typePoint)
		{
			$em = $this->CI->serviceContainer['entity_manager'];
			$type = new PointType();
			$type->setName($typeName);
			$type->setPoint($typePoint);

			try{
				$em->persist($type);
				$em->flush();
			}
			catch(Exception $err){
				die($err->getMessage());
			}
			return true;
		}
	}