<?php

class MY_Exceptions extends CI_Exceptions {
        
    /**
     * render the 404 page
     *
     */
    public function show_404($page = "", $doLogError = TRUE)
    {
        include APPPATH . 'config/routes.php';

        if($page === ""){
            $page = $_SERVER['REQUEST_URI'];
        }

        if ($doLogError){
            log_message('error', '404 Page Not Found --> '.$page);
        }

        if(!empty($route['404_override']) ){
            $CI =& get_instance();

            $CI->output->set_status_header('404'); 
        
             $headerData = [
                'memberId' => $CI->session->userdata('member_id'),
                'title' => 'Page not found | Easyshop.ph',
            ];
            $CI->load->spark('decorator');    
            $CI->load->view('templates/header_primary',  $CI->decorator->decorate('header', 'view', $headerData));
            $CI->load->view('pages/general_error');
            $CI->load->view('templates/footer_primary', $CI->decorator->decorate('footer', 'view'));  

            /**
             * Manually call CSRF Hook
             */
            $csrfHook = new CSRF_Protection();
            $csrfHook->generate_token();
            $csrfHook->inject_tokens();
            exit;
        } 
        else {
            $heading = "404 Page Not Found";
            $message = "The page you requested was not found.";
            echo $this->show_error($heading, $message, 'error_404', 404);
            exit;
        }
    }

} 


