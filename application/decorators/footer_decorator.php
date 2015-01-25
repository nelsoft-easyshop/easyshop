<?php 

class Footer_decorator extends Viewdecorator 
{
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


/* End of file footer_decorator.php */
/* Location: ./application/decorators/footer_decorator.php */

