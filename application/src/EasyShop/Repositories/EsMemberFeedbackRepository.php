<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 

class EsMemberFeedbackRepository extends EntityRepository
{
    
    /**
     * Returns the average ratings of a user
     *
     * @param integer $userId
     * @return mixed
     */
    public function getAverageRatings($userId)
    {
        $ratings = $this->_em->getRepository('EasyShop\Entities\EsMemberFeedback')
                             ->findBy(['forMemberid' => $userId]);

        $averageRatings = array(
            'count' => 0,
            'rating1' => 0,
            'rating2' => 0,
            'rating3' => 0,
        );

        foreach($ratings as $rating){
            $averageRatings['count']++;
            $averageRatings['rating1'] += intval($rating->getRating1());
            $averageRatings['rating2'] += intval($rating->getRating2());
            $averageRatings['rating3'] += intval($rating->getRating3());
        }
        if($averageRatings['count'] > 0){
            $averageRatings['rating1'] /= $averageRatings['count'];
            $averageRatings['rating2'] /= $averageRatings['count'];
            $averageRatings['rating3'] /= $averageRatings['count'];
        }

        return $averageRatings;
    }
}

