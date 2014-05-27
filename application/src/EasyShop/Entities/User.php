<?php
use Doctrine\ORM\Mapping as ORM;
/**
 * @Entity @Table(name="es_member")
 **/
class User{
    /** 
     *  @Id @Column(type="integer") @GeneratedValue
     *  @var int 
     **/
    protected $id_member;
    /**
     *  @Column(type="string", length=255, options={"default":""}) 
     *  @var string
     **/
    protected $username;
    
    /**
     *  @Column(type="string", length=255, options={"default":""}) 
     *  @var string
     **/
    protected $usersession;
    
    /**
     *  @Column(type="string", length=255, options={"default":""}) 
     *  @var string
     **/
    protected $password;
    
    /**
     *  @Column(type="string", length=45, options={"default":""}) 
     *  @var string
     **/
    protected $contactno;
    
    /**
     *  @Column(type="tinyint", length=3, options={"default":"0"}) 
     *  @var smallint
     **/
    protected $is_contactno_verify;
    
    
    
    public function getId(){
        return $this->id;
    }
    
    public function getUsername(){
        return $this->username;
    }
    
    public function setUsername($username){
        return $this->username = $username;
    }


}

