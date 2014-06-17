<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	
class Landingpage extends MY_Controller
{

	function __construct() 
	{
		parent::__construct();
	}
	
	function index()
	{
        echo 'Temporary Landing Page.';
	}
	
  	
    
}// close class

