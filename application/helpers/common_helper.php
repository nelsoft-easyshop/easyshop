<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


/**
*    UTILITY functions for easyshop project
*    @Author: easyshop dev team
*    
*    These functions were originally located in Common.php in System/Core.
*    Has since then been relocated to a helper file to allow for smooth transition
*    just in case CI is updated to a newer version or the framework is changed.
*/ 

if ( ! function_exists('getAssetsDomain'))
{
    function getAssetsDomain()
    {
        $CI =& get_instance(); 
        $CI->load->config('assets', true);
        return $CI->config->item('assetsBaseUrl','assets');
    }

}

 
/**
* Strips unwanted characters for title url encoding
* 
* @access	public
* @param	mixed
* @return	mixed
*/
if ( ! function_exists('es_url_clean'))
{
	function es_url_clean($string)
	{
		$string = preg_replace("/\s+/", " ", $string);
		$string = str_replace('-', ' ', trim($string)); 
		$string = preg_replace("/\s+/", " ", $string);
		$string = str_replace(' ', '-', trim($string));  
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);  

		$string = str_replace('-', ' ', $string); 
		$string = str_replace(' ', '-', $string); 
		$string = str_replace('--', '-', $string);  
		return preg_replace('/\s+/','-', $string);
	}	
}

/**
 * Copy a whole Directory
 *
 * Copy a directory recrusively ( all file and directories inside it )
 *
 * @access    public
 * @param    string    path to source dir
 * @param    string    path to destination dir
 * @return    array
 *
 *  This is a potentially very dangerous function. Make sure that $srcdir and $dstdir
 *  have been validated before calling this. This function will allow for movement of 
 *  files within the project.
 * 
 */    
if(!function_exists('directory_copy'))
{
    function directory_copy($srcdir, $dstdir,$pid,$arrayNameOnly)
    {

        //preparing the paths
        $srcdir=rtrim($srcdir,'/');
        $dstdir=rtrim($dstdir,'/');

        //creating the destination directory
        if(!is_dir($dstdir))mkdir($dstdir, 0777, true);
        
        //Mapping the directory
        $dir_map = directory_map($srcdir);

        foreach($dir_map as $object_key=>$object_value)
        {
            if(is_numeric($object_key)){
                if(in_array(strtolower($object_value),$arrayNameOnly)){
                    copy($srcdir.'/'.$object_value,$dstdir.'/'.$object_value);//This is a File not a directory
                    $filename = explode('_', $object_value);
                    unset($filename[0]);
                    $newFileName =  $pid.'_'.implode('_', $filename);
                    rename($dstdir.'/'.$object_value, $dstdir.'/'.$newFileName);
                }
            }
            else{
                directory_copy($srcdir.'/'.$object_key,$dstdir.'/'.$object_key,$pid,$arrayNameOnly);//this is a directory
            }
        }

        // //Deleting the directory contents
        // delete_files($srcdir, TRUE);
        // rmdir($srcdir);
    }
}

/**
* Ruthlessly strips emoji characters. 
* The need for this can be removed by converting the db collation to utf8mb4
* 
* @access	public
* @param	mixed
* @return	mixed
*/
if ( ! function_exists('es_strip_emoji'))
{
    function es_strip_emoji($text)
    {       
        $clean_text = "";
        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, '', $text);
        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, '', $clean_text);
        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, '', $clean_text);
        // Match flags (iOS)
        $regexTransport = '/[\x{1F1E0}-\x{1F1FF}]/u';
        $clean_text = preg_replace($regexTransport, '', $clean_text);
        return $clean_text;
    }
}



if ( ! function_exists('es_string_limit'))
{
    function es_string_limit($string, $length, $trailer = '...', $postfix='')
    {       
        $final_string = '';
        $lentgh = intval($length,10);
        if((strlen($string) + strlen($postfix)) > $length){
            $final_string = substr($string, 0, $length-strlen($postfix)-strlen($trailer)); 
            $final_string = $final_string.$trailer.$postfix;
        }else{
            $final_string = $string.$postfix;
        }
        return $final_string;
    }
}

if ( ! function_exists('make_array'))
{
    function make_array($array = array(), $key = ''){
	  $key = ($key = '')?key($array):$key;
	  $temp = $array;
	  $array = array();
	  $array[0] = $temp;
	  return $array;
    }
}

if ( ! function_exists('is_assoc'))
{
    function is_assoc($array) {
	return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}







