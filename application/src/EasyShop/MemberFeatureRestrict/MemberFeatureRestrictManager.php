<?php

namespace Easyshop\MemberFeatureRestrict;

class MemberFeatureRestrictManager
{
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Class constructor. Loads dependencies.
     *
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Get all the list of feature with restriction
     * 
     * @param integer $memberId
     * @return mixed
     */
    public function getAllowedFeaturesForMember($memberId)
    {
        $featuresObj = $this->em->getRepository('EasyShop\Entities\EsFeatureRestrict')
                                ->getFeatures();
        $features = [];

        foreach ($featuresObj as $feature) {
            $isMemberAllowedInFeature = $this->em->getRepository('EasyShop\Entities\EsMemberFeatureRestrict')
                                                 ->checkIfMemberIsAllowedInFeature($feature, $memberId);
            $features[$feature->getIdFeatureRestrict()] = $isMemberAllowedInFeature;
        }

        return $features;
    }

    /**
     * Insert member to EsMemberFeatureRestrict
     * @param $memberId
     * @param $featureId
     * @return EasyShop\Entities\EsMemberFeatureRestrict
     */
    public function addMemberToFeature($memberId, $featureId)
    {
        $featureRestrictEntity = $this->em->find('EasyShop\Entities\EsFeatureRestrict', $featureId);
        $memberEntity = $this->em->find('EasyShop\Entities\EsMember', $memberId);
        $memberFeatureRestrictEntity = '';

        if ($featureRestrictEntity) {
            $isMemberAllowedInFeature = $this->em->getRepository('EasyShop\Entities\EsMemberFeatureRestrict')
                                                 ->checkIfMemberIsAllowedInFeature($featureRestrictEntity, $memberId);
            $isFeatureFull = $this->em->getRepository('EasyShop\Entities\EsMemberFeatureRestrict')
                                      ->checkIfFeatureIsFull($featureRestrictEntity);

            if (!$isMemberAllowedInFeature && !$isFeatureFull) {
                $memberFeatureRestrictEntity = $this->em->getRepository('EasyShop\Entities\EsMemberFeatureRestrict')
                                                        ->addMemberToFeature($memberEntity, $featureRestrictEntity, 0);
            }

        }

        return $memberFeatureRestrictEntity;
    }

}
