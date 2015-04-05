<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsActivityType as EsActivityType;
use EasyShop\Entities\EsActivityHistory as EsActivityHistory;

class EsActivityHistoryRepository extends EntityRepository
{   
    /**
     * Create and insert log in to table
     * @param  integer $activityType
     * @param  string  $jsonData
     * @param  \EasyShop\Entities\EsMember $member
     * @return \EasyShop\Entities\EsActivityHistory
     */
    public function createAcitivityLog($activityType, $jsonData, $member)
    {   
        $this->em =  $this->_em;
        $activity = new EsActivityHistory();
        $activity->setActivityType($activityType);
        $activity->setJsonData($jsonData);
        $activity->setMember($member);
        $activity->setActivityDatetime(date_create(date("Y-m-d H:i:s")));
        $this->em->persist($activity);
        $this->em->flush();
    }
}
