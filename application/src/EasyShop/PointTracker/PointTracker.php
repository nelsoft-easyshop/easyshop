<?php
	
	namespace EasyShop\PointTracker;

	require_once('../application/src/EasyShop/Entities/EsPointHistory.php');
	use EasyShop\Entities\EsPointHistory;

	require_once('../application/src/EasyShop/Entities/EsPoint.php');
	use EasyShop\Entities\EsPoint;


	/**
	 * Point Tracker Class
	 *
	 * @author LA roberto <la.roberto@easyshop.ph>
	 */
	class PointTracker
	{
		

		/**
	     * Entity Manager instance
	     *
	     * @var Doctrine\ORM\EntityManager
	     */
		private $em;


		/**
	     * Constructor. Retrieves Entity Manager instance
	     * 
	     */
		public function __construct()
		{
			$this->em = get_instance()->kernel->serviceContainer['entity_manager'];
		}


		/**
	     * Updates point history and adds point to a user
	     *
	     * @param int $userId ID of a user
	     * @param int $actionId ID of an action
	     *
	     * @return boolean
	     */
		public function addUserPoint($userId, $actionId)
		{
			// Get Point Type object
			$points = $this->em->getRepository('EasyShop\Entities\EsPointType')
									->find($actionId);

			if($points === null){
				return false;
			}

			// Get Member object
			$user = $this->em->getRepository('EasyShop\Entities\EsMember')
									->find($userId);

			if($user === null){
				return false;
			}

			// Get Point object
			$userPoint = $this->em->getRepository('EasyShop\Entities\EsPoint')
										->findOneBy(['m' => $userId]);

			// Insert to point history
			$pointHistory = new EsPointHistory();
			$pointHistory->setM($user);
			$pointHistory->setType($points);
			$pointHistory->setDateAdded(date_create(date("Y-m-d H:i:s")));
			$pointHistory->setPoint($points->getPoint());

			$this->em->persist($pointHistory);
			$this->em->flush();

			if($userPoint !== null){
				// Update existing user	
				$userPoint->setPoint($userPoint->getPoint() + $points->getPoint()); 
				$this->em->flush();
			}
			else{
				// Insert new user
				$userPoint = new EsPoint();
				$userPoint->setPoint($points->getPoint());
				$userPoint->setM($user);

				$this->em->persist($userPoint);
				$this->em->flush();
			}	

			return true;				
		}


		/**
	     * Returns the ID of a specific action string
	     *
	     * @param string $actionString action string to be searched
	     *
	     * @return bool|int
	     */
		public function getActionId($actionString)
		{
			$pointType = $this->em->getRepository('EasyShop\Entities\EsPointType')
										->findOneBy(['name' => $actionString]);
			
			return $pointType === null? false : $pointType->getId();
		}


		/**
	     * Returns the current points earned by a user
	     *
	     * @param id $userId ID of user to be searched
	     *
	     * @return bool|int
	     */
		public function getUserPoint($userId)
		{
			$user = $this->em->getRepository('EasyShop\Entities\EsPoint')
									->findOneBy(['m' => $userId]);

			return $user === null? false : $user->getPoint();
		}		


		/**
	     * Returns all data inside Point History Table
	     *
	     * @return mixed
	     */
		public function getPointHistory()
		{
			return $this->em->getRepository('EasyShop\Entities\EsPointHistory')
									->findAll();
		}
	}