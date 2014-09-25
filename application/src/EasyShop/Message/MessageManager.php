<?php

class MessageManager {

    /**
     *  Entity Manager Instance
     *
     *  @var Doctrine\ORM\EntityManager
     */
    private $em;

    function __construct($em)
    {
        $this->em = $em;
    }

    function send()
    {

    }
}