<?php
    
namespace EasyShop\PointTracker;

use EasyShop\Entities\EsPointHistory;
use EasyShop\Entities\EsPoint;
use EasyShop\Entities\EsPointType as EsPointType;
use EasyShop\PaymentGateways\PointGateway as PointGateway;

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

    const POINT_DAYS_DURATION = 90;

    /**
     * Constructor. Retrieves Entity Manager instance
     *
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Updates point history and adds point to a user
     *
     * @param int   $userId ID of a user
     * @param int   $actionId ID of an action
     * @param bool  $isPercentage
     * @param float $price
     *
     * @return boolean
     */
    public function addUserPoint($userId, $actionId, $percentage = 0, $customPoints = 0)
    {
        if(PointGateway::POINT_ENABLED){
            // Get Point Type object
            $points = $this->em->getRepository('EasyShop\Entities\EsPointType')
                               ->find($actionId);

            if ($points === null) {
                return false;
            }

            // Get Member object
            $user = $this->em->getRepository('EasyShop\Entities\EsMember')
                             ->find($userId);

            if ($user === null) {
                return false;
            }

            // Get Point object
            $userPoint = $this->em->getRepository('EasyShop\Entities\EsPoint')
                                  ->findOneBy(['member' => $userId]);

            if ($points->getId() === EsPointType::TYPE_REVERT) {
                $addPoints = $customPoints;
            }
            else {
                if ($percentage > 0) {
                    $addPoints = bcmul($points->getPoint(), bcdiv($percentage, 100, 4), 4);
                }
                else {
                    $addPoints = $points->getPoint();
                }
            }

            // Insert to point history
            $pointHistory = new EsPointHistory();
            $pointHistory->setMember($user);
            $pointHistory->setType($points);
            $pointHistory->setDateAdded(date_create(date("Y-m-d H:i:s")));
            $pointHistory->setPoint($addPoints);

            $this->em->persist($pointHistory);
            $this->em->flush();

            if ($userPoint !== null) {
                // Update existing user
                $userPoint->setPoint($userPoint->getPoint() + $addPoints);
                $userPoint->setExpirationDate(date_create(date("Y-m-d H:i:s", strtotime("+".self::POINT_DAYS_DURATION." days"))));

                $this->em->flush();
            }
            else {
                // Insert new user
                $userPoint = new EsPoint();
                $userPoint->setPoint($addPoints);
                $userPoint->setMember($user);
                $userPoint->setExpirationDate(date_create(date("Y-m-d H:i:s", strtotime("+".self::POINT_DAYS_DURATION." days"))));

                $this->em->persist($userPoint);
                $this->em->flush();
            }

            return true;
        }

        return false;
    }


    /**
     * Updates point history and deducts point to a user
     *
     * @param int $userId ID of a user
     * @param int $typeId ID of point type
     * @param int $points Points to be deducted from the user
     *
     * @return bool|int
     */
    public function spendUserPoint($userId, $typeId, $points)
    {
        if(PointGateway::POINT_ENABLED){
            $points = abs($points);

            // Get deduct point type instance
            $deduct = $this->em->getRepository('EasyShop\Entities\EsPointType')
                               ->find($typeId);

            // Get Point object
            $userPoint = $this->em->getRepository('EasyShop\Entities\EsPoint')
                                  ->findOneBy(['member' => $userId]);

            // Get Member object
            $user = $this->em->getRepository('EasyShop\Entities\EsMember')
                             ->find($userId);

           
            if ($userPoint === null || $userPoint->getPoint() < $points ||
                $deduct === null || $user === null) {
                return false;
            }
            else {
                $userPoint->setPoint($userPoint->getPoint() - $points);

                // Update points history table
                $pointHistory = new EsPointHistory();
                $pointHistory->setMember($user);
                $pointHistory->setType($deduct);
                $pointHistory->setDateAdded(date_create(date("Y-m-d H:i:s")));
                $pointHistory->setPoint(-$points);

                $this->em->persist($pointHistory);
                $this->em->flush();
                return $pointHistory;
            }
        }

        return false;
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
     * Returns the point equivalent of a specific action id
     *
     * @param int $actionId action id to be searched
     *
     * @return bool|int
     */
    public function getActionPoint($actionId)
    {
        $pointType = $this->em->getRepository('EasyShop\Entities\EsPointType')
                                ->find($actionId);

        return $pointType === null? false : $pointType->getPoint();
    }


    /**
     * Returns the current points earned by a user
     *
     * @param id $userId ID of user to be searched
     *
     * @return bool|string
     */
    public function getUserPoint($userId)
    {
        $user = $this->em->getRepository('EasyShop\Entities\EsPoint')
                            ->findOneBy(['member' => $userId]);

        return $user === null? false : $user->getPoint();
    }
}
