<?php 

$path = dirname(__FILE__).'/../../web/assets/temp_product/*';
$files = glob($path);

foreach($files as $file){ 
 

    $datecreated = date ("Y-m-d H:i:s.", filectime($file));
    $now = date("Y-m-d H:i:s");

    if(intval(round((strtotime($now)) - strtotime($datecreated))/3600) >= 3){
        if(is_dir($file)){
            deleteDir($file);
        }
        else{
             unlink($file);
        }
    }


}
echo 'Done. The directory '.$path.' has been emptied.';


/*
 *   Deletes a directory and all its content. Recursive implementation.
 */

function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}
