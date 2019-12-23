<?php

/*

ob_start();
print_r($keywordInfo);
echo "\n";
echo "seUrl : ".$seUrl;
echo "\n";
echo "searchUrl : ".$searchUrl;
echo "\n";
$msg = ob_get_clean();

*/

// $msg = '';
// $log_file = $_SERVER['DOCUMENT_ROOT'].'/_log.txt'; // dirname( __FILE__ )
// error_log($msg, 3, $log_file);

// mail("joel@pizzotti.ch",'crawlKeyword',$msg);


$file = dirname( __FILE__ ).'/txt/report_'.date("Y-m-d").'_'.$keywordInfo['id'].'_'.$seInfoId.'.txt';

if( file_exists($file) && filesize($file) > 0 ) {

    $fp = fopen($file, "r");
    $result_json = fread($fp, filesize($file) );
    fclose($fp);

    $result = json_decode($result_json, true);

} else {

    if( is_dir(dirname($file)) ) {} else {
        mkdir(dirname($file));
    }

    $result = $this->spider->getContent($seUrl);

    if( is_writable(dirname($file)) ) {

        $result_json = json_encode($result);

        // si le captcha de Google apparait
        if( strlen($result_json) > 4000 ) {

            $fp = fopen($file, "w");
            fputs($fp, $result_json);
            fclose($fp);

        }

        $file_id = dirname( __FILE__ ).'/_perso_flat_file_id.php';
        if( file_exists($file_id) && filesize($file_id) > 0 ) {
        	include($file_id);
        }

    }

}


?>