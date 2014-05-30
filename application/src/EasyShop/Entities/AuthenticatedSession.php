<?php

namespace Easyshop\Entities;



/**
 * @Entity
 * @Table(name="es_authenticated_session")
 */
class AuthenticatedSession
{
    /**
     * @Id
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="member_id", referencedColumnName="id_member", onDelete="CASCADE")
     * 
     * @var int
     */
    protected $user;
    
    /**
     * @Id
     * @OneToOne(targetEntity="Session")
     * @JoinColumn(name="session_id", referencedColumnName="session_id", onDelete="CASCADE")
     * 
     * @var string
     */
    protected $session;
}
