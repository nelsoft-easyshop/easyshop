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
     * @param  string  $activityString
     * @param  date    $dateTime
     * @return object
     */
    public function createAcitivityLog($activityType, $activityString)
    {   
        $this->em =  $this->_em;
        $activity = new EsActivityHistory();
        $activity->setActivityType($activityType);
        $activity->setActivityString($activityString);
        $activity->setActivityDatetime(date_create(date("Y-m-d H:i:s")));
        $this->em->persist($activity);
        $this->em->flush();
    }
}
