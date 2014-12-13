<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Viewdecorator extends MY_Controller {

    protected $view_data;

    public function get_decorated_data()
    {
        return $this->view_data;
    }
}