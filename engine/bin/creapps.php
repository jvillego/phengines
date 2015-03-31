<?php

define('ENGINE', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);

if(count($argv) > 1){

	switch($argv[1]){
		case "help":
			echo "Creapps Help\n";
			echo "\n";
			echo "To crearte new app execute:\n";
			echo "php creapps.php create <AppName> <Path_to_www_or_htdocs>\n\n";
		break;
		case "create":
			if(isset($argv[2])&&isset($argv[3])){
				if(file_exists($argv[3])){
					echo "creating app \"{$argv[2]}\" in \"{$argv[3]}\"";
					recursive_copy(ENGINE.DS."templates".DS."app", $argv[3].DS.$argv[2]);
					echo "\ndone!\n";
				}else{
					echo "Error on path parameter,\nDestination \"{$argv[3]}\"  not found! \n";
				}
			}else{
				echo "Error, please use:\nphp creapps.php create <AppName> <Path_to_www_or_htdocs>\n\n";
			}
		break;

	}

}else{

	echo "Creapps usage mode:\n";
	echo "php  creapps.php command options\n";
	echo "Write: php creapps.php help  to see more information.\n";

}

function recursive_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if( $file != '.svn' )
            if (( $file != '.' ) && ( $file != '..' ) ) { 
                if ( is_dir($src . '/' . $file) ) { 
                    recursive_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
    } 
    closedir($dir); 
} 

?>
