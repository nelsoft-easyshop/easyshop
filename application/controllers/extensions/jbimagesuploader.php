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
class JbimagesUploader extends MY_Controller 
{   
    /**
     * Flag if the upload is allowed or not
     *
     */
    const ALLOW_UPLOAD = true;
    
    /**
     * Class Contructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        
        if(!self::ALLOW_UPLOAD){
            return false;
        }
        
        $this->load->helper(array('language'));
        $this->config->load('jbimages', TRUE);
        $this->config->load('assets', TRUE);
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
     * @param boolean $useAws
     */
    public function upload ($lang='english')
    {
        $useAws = false;
        if(strtolower(ENVIRONMENT) !== 'development'){
            $useAws = true;
        } 
    
        $this->_lang_set($lang);
        $awsUploader = $this->serviceContainer['aws_uploader'];
        
        $conf['img_path']           = $this->config->item('img_path',       'jbimages');
        $conf['full_img_path']      = $this->config->item('full_img_path',  'jbimages');
        $conf['allow_resize']       = $this->config->item('allow_resize',   'jbimages');
        
        $config['allowed_types']    = $this->config->item('allowed_types',  'jbimages');
        $config['max_size']         = $this->config->item('max_size',       'jbimages');
        $config['encrypt_name']     = $this->config->item('encrypt_name',   'jbimages');
        $config['overwrite']        = $this->config->item('overwrite',      'jbimages');
        $config['upload_path']      = $this->config->item('upload_path',    'jbimages');
        
        if (!$conf['allow_resize'])
        {
            $config['max_width']    = $this->config->item('max_width',      'jbimages');
            $config['max_height']   = $this->config->item('max_height',     'jbimages');
        }
        else
        {
            $conf['max_width']      = $this->config->item('max_width',      'jbimages');
            $conf['max_height']     = $this->config->item('max_height',     'jbimages');
            
            if ($conf['max_width'] == 0 and $conf['max_height'] == 0)
            {
                $conf['allow_resize'] = FALSE;
            }
        }
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $fileCount = count($_FILES['descriptionfile']['name']);

        for($i = 0; $i < $fileCount; $i++){
        
            foreach($_FILES['descriptionfile'] as $fieldname=>$fieldvalue){
                $_FILES['userfile'][$fieldname] = $fieldvalue[$i];
            }
            
            if ($this->upload->do_upload()) {
                $result = $this->upload->data();
                
                if ($conf['allow_resize'] && $conf['max_width'] > 0 && $conf['max_height'] > 0 && 
                    (($result['image_width'] > $conf['max_width']) || ($result['image_height'] > $conf['max_height']))
                ){
                    $resizeParams = array(
                        'source_image'  => $result['full_path'],
                        'new_image'     => $result['full_path'],
                        'width'         => $conf['max_width'],
                        'height'        => $conf['max_height']
                    );
                    $this->load->library('image_lib');
                    $this->image_lib->initialize($resizeParams);  
                    $this->image_lib->resize(); 
                    $this->image_lib->clear();
                }
                
                $result['result']       = "file_uploaded";
                $result['resultcode']   = 'ok';
                $result['file_name']    = $conf['img_path'] . '/' . $result['file_name'];
                $result['base_url'] =  rtrim(base_url(), '/');
                
                if($useAws){
                    if($awsUploader->uploadFile($result['full_path'],  $result['file_name'])){
                        $result['base_url'] =   rtrim($this->config->item('assetsBaseUrl', 'assets'), '/');
                         unlink($result['full_path']);
                    }
                    else{
                        $result['result']       = "S3 upload unsuccessful";
                        $result['resultcode']   = 'failed';
                        unset($result['file_name']); 
                        unset($result['base_url']);
                    }
                }
         
            }
            else {
                $result['result']       = $this->upload->display_errors(' ', ' ');
                $result['resultcode']   = 'failed';
            }

            if($i === $fileCount - 1 ){
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
/* Location: ./application/controllers/extensions/jbimagesuploader.php */
