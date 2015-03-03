<?php

namespace Easyshop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsFeatureRestrict;

class EsFeatureRestrictRepository extends EntityRepository
{

    /**
     * Returns declared feature id constants
     * @return ARRAY
     */
    public function getFeatureIds()
    {
        return [
            EsFeatureRestrict::REAL_TIME_CHAT
        ];
    }

    /**
     * Returns the feature restrict entity
     * @return mixed
     */
    public function getFeatures()
    {
        $featureIds = $this->getFeatureIds();
        $query =  $this->_em->createQueryBuilder()
                            ->select('tbl_fr')
                            ->from('EasyShop\Entities\EsFeatureRestrict', 'tbl_fr')
                            ->where('tbl_fr.idFeatureRestrict IN (:ids)')
                            ->setParameter('ids', $featureIds)
                            ->getQuery();

        return $query->getResult();
    }
}
