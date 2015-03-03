<?php

namespace Easyshop\Repositories;

use Doctrine\ORM\EntityRepository;

class EsMemberFeatureRestrictRepository extends EntityRepository
{

    /**
     * Check if member is allowed in a specific feature
     * @param $feature
     * @param $memberId
     * @return bool
     */
    public function isMemberAllowedInFeature($feature, $memberId)
    {
        $query =  $this->_em->createQueryBuilder()
                            ->select('tbl_mfr')
                            ->from('EasyShop\Entities\EsMemberFeatureRestrict', 'tbl_mfr')
                            ->where('tbl_mfr.member = :memberId')
                            ->andWhere('tbl_mfr.featureRestrict = :featureId')
                            ->setParameter('featureId', $feature->getIdFeatureRestrict())
                            ->setParameter('memberId', $memberId)
                            ->getQuery();
        $isMemberInFeatureRestrict = $query->getResult();

        $query =  $this->_em->createQueryBuilder()
                            ->select('COUNT(tbl_mfr)')
                            ->from('EasyShop\Entities\EsMemberFeatureRestrict', 'tbl_mfr')
                            ->where('tbl_mfr.featureRestrict = :featureId')
                            ->having('COUNT(tbl_mfr.idMemberFeatureRestrict) = :maxUser')
                            ->setParameter('featureId', $feature->getIdFeatureRestrict())
                            ->setParameter('maxUser', $feature->getMaxUser())
                            ->getQuery();
        $isFeatureFull = $query->getResult();

        return [
            'isMemberInFeatureRestrict' => (bool) $isMemberInFeatureRestrict,
            'isFeatureFull' => (bool) $isFeatureFull,
        ];
    }
}
