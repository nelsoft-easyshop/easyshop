<?php 

class Footer_decorator extends Viewdecorator 
{

    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Generate footer data
     *
     */
    public function view()
    {
        $socialMediaLinks = $this->serviceContainer['social_media_manager']
                                 ->getSocialMediaLinks();
        $this->view_data['facebook'] =  $socialMediaLinks["facebook"];
        $this->view_data['twitter'] =  $socialMediaLinks["twitter"];
    }

}

