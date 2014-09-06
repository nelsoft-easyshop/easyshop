<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * EsAuthenticatedSession
 *
 * @ORM\Table(name="es_authenticated_session", indexes={@ORM\Index(name="IDX_AC8D08427597D3FE", columns={"member_id"}), @ORM\Index(name="IDX_AC8D0842613FECDF", columns={"session_id"})})
 * @ORM\Entity
 */
class EsAuthenticatedSession
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_authenticated_session", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAuthenticatedSession;

    /**
     * @var \EasyShop\Entities\CiSessions
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\CiSessions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="session_id", referencedColumnName="session_id")
     * })
     */
    private $session;

    /**
     * @var \EasyShop\Entities\EsMember
     *
     * @ORM\ManyToOne(targetEntity="EasyShop\Entities\EsMember")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="member_id", referencedColumnName="id_member")
     * })
     */
    private $member;



    /**
     * Get idAuthenticatedSession
     *
     * @return integer 
     */
    public function getIdAuthenticatedSession()
    {
        return $this->idAuthenticatedSession;
    }

    /**
     * Set session
     *
     * @param \EasyShop\Entities\CiSessions $session
     * @return EsAuthenticatedSession
     */
    public function setSession(\EasyShop\Entities\CiSessions $session = null)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session
     *
     * @return \EasyShop\Entities\CiSessions 
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set member
     *
     * @param \EasyShop\Entities\EsMember $member
     * @return EsAuthenticatedSession
     */
    public function setMember(\EasyShop\Entities\EsMember $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \EasyShop\Entities\EsMember 
     */
    public function getMember()
    {
        return $this->member;
    }
}
