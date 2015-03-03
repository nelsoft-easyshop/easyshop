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
     * where key is the EsFeatureRestrict ID and value is bool
     * @param $memberId
     * @return array
     */
    public function getListOfFeatureWithRestrictionByMemberId($memberId)
    {
        $featuresObj = $this->em->getRepository('EasyShop\Entities\EsFeatureRestrict')
                                ->getFeatures();
        $features = [];

        foreach ($featuresObj as $feature) {
            $isMemberAllowedInFeature = $this->em->getRepository('EasyShop\Entities\EsMemberFeatureRestrict')
                                                 ->checkIfMemberIsAllowedInFeature($feature, $memberId);
            $isFeatureIsFull = $this->em->getRepository('EasyShop\Entities\EsMemberFeatureRestrict')
                                        ->checkIfFeatureIsFull($feature);
            $features[$feature->getIdFeatureRestrict()] = [
                                                              'isMemberAllowedInFeature' => $isMemberAllowedInFeature,
                                                              'isFeatureIsFull' => $isFeatureIsFull
                                                          ];
        }

        return $features;
    }

}
