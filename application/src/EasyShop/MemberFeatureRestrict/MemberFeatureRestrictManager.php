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
     * @param $memberId
     */
    public function getListOfFeatureWithRestrictionByMemberId($memberId)
    {
        echo $memberId;
    }

}
