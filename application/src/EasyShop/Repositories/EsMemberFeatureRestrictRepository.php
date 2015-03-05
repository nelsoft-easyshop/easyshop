<?php

namespace Easyshop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsMemberFeatureRestrict;

class EsMemberFeatureRestrictRepository extends EntityRepository
{

    /**
     * Check if member is allowed in a specific feature
     * @param obj $feature
     * @param int $memberId
     * @return bool
     */
    public function checkIfMemberIsAllowedInFeature($feature, $memberId)
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

        return (bool) $isMemberInFeatureRestrict;
    }

    /**
     * Check if Feature is full
     * @param obj $feature
     * @return bool
     */
    public function checkIfFeatureIsFull($feature)
    {
        $query =  $this->_em->createQueryBuilder()
                            ->select('COUNT(tbl_mfr)')
                            ->from('EasyShop\Entities\EsMemberFeatureRestrict', 'tbl_mfr')
                            ->where('tbl_mfr.featureRestrict = :featureId')
                            ->having('COUNT(tbl_mfr.idMemberFeatureRestrict) >= :maxUser')
                            ->setParameter('featureId', $feature->getIdFeatureRestrict())
                            ->setParameter('maxUser', $feature->getMaxUser())
                            ->getQuery();
        $isFeatureFull = $query->getResult();

        return (bool) $isFeatureFull;
    }

    /**
     * Create a new member feature restrict
     * @param obj $member
     * @param obj $feature
     * @param int $isDelete
     * @return EasyShop\Entities\EsMemberFeatureRestrict
     */
    public function addMemberToFeature($member, $feature, $isDelete)
    {
        $memberFeatureRestrict = new EsMemberFeatureRestrict();
        $memberFeatureRestrict->setMember($member);
        $memberFeatureRestrict->setFeatureRestrict($feature);
        $memberFeatureRestrict->setIsDelete($isDelete);
        $this->_em->persist($memberFeatureRestrict);
        $this->_em->flush();

        return $memberFeatureRestrict;
    }
}
