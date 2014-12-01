<?php
/*
 |	This script is used to update config/param/controllers.php 
 |	which contains an array of all controllers existing.
 |	Array is used and checked when vendor slug is being changed.
 |	All controller names should be restricted for vendor slug names
 */
 
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "\nScript running! \n";

# Path for controllers dir
echo "Loading controllers directory... \n";
$path = dirname(__FILE__) . '/../controllers/*';
$myFiles = glob($path);

# Path for controllers config file
echo "Defining controllers.php config file and directory... \n";
$configFile = dirname(__FILE__) . '/../config/param/controllers.php';
$controllerConfig = array();

# Execute extraction of controller filenames
echo "Extracting files from controllers directory... \n";
getDirContents($myFiles);

# Overwrite config file with new array
echo "Creating controllers.php configuration file ... \n";
file_put_contents($configFile, '<?php $controllerConfig = ' . var_export( $controllerConfig, true ) . '; return $controllerConfig;' );

echo "\n\nControllers.php created! Execution complete! \n";

# Gets filename for all files inside directory
function getDirContents($files)
{
    global $controllerConfig;
    foreach($files as $file){
        if( is_dir($file) ){
            $newFiles = glob($file.'/*');
            getDirContents($newFiles);
        }else{
            $path_parts = pathinfo($file);
            if( $path_parts['extension'] === "php" ){
                if( !in_array($path_parts['basename'], $controllerConfig) ){
                    $controllerConfig[] = $path_parts['filename'];
                }
            }
        }
    }
}
