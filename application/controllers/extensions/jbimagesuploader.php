<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
  * JBIMAGES upload plugin for tinyMCE
  * 
  * The plugin is written under codeigniter and has been integrated with the
  * application to allow for more flexible code customization.
  * See https://github.com/vikdiesel/justboil.me for further references.
  * 
  * Changes:
  * 1. Allowed multiple file upload
  * 2. Added option to upload to an AWS S3 bucket
  *
  * @author Viktor Kuzhelny (Original Author) <vik@justboil.co.uk>
  * @author Stephen Serafico <stephen@easyshop.ph>
  * @author Sam Gavinio <samgavinio@easyshop.ph>
  *
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
    
    /**
     * The default page
     *
     * @param string $lang
     */
    public function index($lang='english')
    {
        $this->blank($lang);
    }
    
    /**
     * Sets the language used
     *
     * @param string $lang
     */
    private function _lang_set($lang)
    {
        $this->config->set_item('language', $lang);
        $this->lang->load('jbstrings', $lang);
    }
    
    
    /**
     * Upload the files
     *
     * @param string $lang
     */
    public function upload ($lang='english')
    {
        $this->_lang_set($lang);
        $conf['img_path']           = $this->config->item('img_path',		'uploader_settings');
        $conf['full_img_path']      = $this->config->item('full_img_path',	'uploader_settings');
        $conf['allow_resize']       = $this->config->item('allow_resize',	'uploader_settings');
        
        $config['allowed_types']    = $this->config->item('allowed_types',	'uploader_settings');
        $config['max_size']         = $this->config->item('max_size',		'uploader_settings');
        $config['encrypt_name']     = $this->config->item('encrypt_name',	'uploader_settings');
        $config['overwrite']        = $this->config->item('overwrite',		'uploader_settings');
        $config['upload_path']      = $this->config->item('upload_path',	'uploader_settings');
        
        if (!$conf['allow_resize'])
        {
            $config['max_width']    = $this->config->item('max_width',		'uploader_settings');
            $config['max_height']   = $this->config->item('max_height',		'uploader_settings');
        }
        else
        {
            $conf['max_width']      = $this->config->item('max_width',		'uploader_settings');
            $conf['max_height']     = $this->config->item('max_height',		'uploader_settings');
            
            if ($conf['max_width'] == 0 and $conf['max_height'] == 0)
            {
                $conf['allow_resize'] = FALSE;
            }
        }

        $this->load->library('upload', $config);
        $filecount = count($_FILES['descriptionfile']['name']);

        for($fx = 0; $fx < $filecount; $fx++){
            foreach($_FILES['descriptionfile'] as $fieldname=>$fieldvalue){
                $_FILES['userfile'][$fieldname] = $fieldvalue[$fx];
            }
            
            if ($this->upload->do_upload()) {
                $result = $this->upload->data();
                if ($conf['allow_resize'] and $conf['max_width'] > 0 and $conf['max_height'] > 0 and (($result['image_width'] > $conf['max_width']) or ($result['image_height'] > $conf['max_height']))){
                    $resizeParams = array
                    (
                        'source_image'  => $result['full_path'],
                        'new_image'     => $result['full_path'],
                        'width'         => $conf['max_width'],
                        'height'        => $conf['max_height']
                    );
                    
                    $this->load->library('image_lib', $resizeParams);
                    $this->image_lib->resize();
                }

                $result['result']		= "file_uploaded";
                $result['resultcode']	= 'ok';
                $result['file_name']	= $conf['img_path'] . '/' . $result['file_name'];
            }
            else {
                $result['result']		= $this->upload->display_errors(' ', ' ');
                $result['resultcode']	= 'failed';
            }
            if($filecount-1 === $fx){
                $result['status'] = 'last';
            }
            else{
                $result['status'] = 'notlast';
            }
            $this->load->view('extensions/jbimages/ajax_upload_result', $result);
        }
    }
    
    /**
     * Renders a blank page for the iframe
     *
     * @param string $lang
     */
    public function blank($lang='english')
    {
        $this->_lang_set($lang);
        $this->load->view('extensions/jbimages/blank');
    }
    

}

/* End of file uploader.php */
/* Location: ./application/controllers/uploader.php */
