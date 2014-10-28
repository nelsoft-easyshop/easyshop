<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *   Changes by Janz - 2/27/2014
 *   Function : upload
 *   Edited function to be able to upload multiple pictures
 *   Input name "userfile" (CI default) changed to "descriptionfile"
 *   Each data from descriptionfile passed on to "userfile" to avoid 
 *                   changes in CI upload library
 *   Part of code placed inside for loop
 *   Added "status" to result to indicate last image and close jbdialog box
 */
class JbimagesUploader extends CI_Controller 
{
    
    
    /**
     * Flag if the upload is allowed or not
     *
     */
    CONSTANT ALLOW_UPLOAD = true;
    
    /**
     * Class Contructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        
        if(!ALLOW_UPLOAD){
            return false;
        }
        
        $this->load->helper(array('language'));
        $this->config->load('jbimages', TRUE);
    }
    
    /* Language set */
    
    private function _lang_set($lang)
    {
        // We accept any language set as lang_id in **_dlg.js
        // Therefore an error will occur if language file doesn't exist
        
        $this->config->set_item('language', $lang);
        $this->lang->load('jbstrings', $lang);
    }
    
    /* Default upload routine */
        
    public function upload ($lang='english')
    {
        // Set language
        $this->_lang_set($lang);
        
        // Get configuartion data (we fill up 2 arrays - $config and $conf)
        
        $conf['img_path']			= $this->config->item('img_path',		'uploader_settings');
        $conf['full_img_path']		= $this->config->item('full_img_path',	'uploader_settings');
        $conf['allow_resize']		= $this->config->item('allow_resize',	'uploader_settings');
        
        $config['allowed_types']	= $this->config->item('allowed_types',	'uploader_settings');
        $config['max_size']			= $this->config->item('max_size',		'uploader_settings');
        $config['encrypt_name']		= $this->config->item('encrypt_name',	'uploader_settings');
        $config['overwrite']		= $this->config->item('overwrite',		'uploader_settings');
        $config['upload_path']		= $this->config->item('upload_path',	'uploader_settings');
        
        if (!$conf['allow_resize'])
        {
            $config['max_width']	= $this->config->item('max_width',		'uploader_settings');
            $config['max_height']	= $this->config->item('max_height',		'uploader_settings');
        }
        else
        {
            $conf['max_width']		= $this->config->item('max_width',		'uploader_settings');
            $conf['max_height']		= $this->config->item('max_height',		'uploader_settings');
            
            if ($conf['max_width'] == 0 and $conf['max_height'] == 0)
            {
                $conf['allow_resize'] = FALSE;
            }
        }
        
        // Load uploader
        $this->load->library('upload', $config);
        
        /*
        foreach($_FILES['descriptionfile']['type'] as $key=>$value){
            print('This loop');
            print($value);
        }
        die();
        */
        
        // Get total # of files
        $filecount = count($_FILES['descriptionfile']['name']);
        
        // For loop and foreach loop added to cycle through each image
        // Code to upload not changed
        for($fx = 0; $fx < $filecount; $fx++){
            //cycles through name, type, tmp_name, error, and size
            foreach($_FILES['descriptionfile'] as $fieldname=>$fieldvalue){
                $_FILES['userfile'][$fieldname] = $fieldvalue[$fx];
            }
            
            if ($this->upload->do_upload()) // Success
            {
                // General result data
                $result = $this->upload->data();
                
                // Shall we resize an image?
                if ($conf['allow_resize'] and $conf['max_width'] > 0 and $conf['max_height'] > 0 and (($result['image_width'] > $conf['max_width']) or ($result['image_height'] > $conf['max_height'])))
                {				
                    // Resizing parameters
                    $resizeParams = array
                    (
                        'source_image'	=> $result['full_path'],
                        'new_image'		=> $result['full_path'],
                        'width'			=> $conf['max_width'],
                        'height'		=> $conf['max_height']
                    );
                    
                    // Load resize library
                    $this->load->library('image_lib', $resizeParams);
                    
                    // Do resize
                    $this->image_lib->resize();
                }
                
                // Add our stuff
                $result['result']		= "file_uploaded";
                $result['resultcode']	= 'ok';
                $result['file_name']	= $conf['img_path'] . '/' . $result['file_name'];
            }
            else // Failure
            {
                // Compile data for output
                $result['result']		= $this->upload->display_errors(' ', ' ');
                $result['resultcode']	= 'failed';
            }
            if($filecount-1 === $fx){
                $result['status'] = 'last';
            }
            else{
                $result['status'] = 'notlast';
            }
            // Output to user
            $this->load->view('extensions/jbimages/ajax_upload_result', $result);
        }
    }
    
    /* Blank Page (default source for iframe) */
    
    public function blank($lang='english')
    {
        $this->_lang_set($lang);
        $this->load->view('extensions/jbimages/blank');
    }
    
    public function index($lang='english')
    {
        $this->blank($lang);
    }
}

/* End of file uploader.php */
/* Location: ./application/controllers/uploader.php */
