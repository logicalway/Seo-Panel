<?php

ob_start();
print_r($keywordInfo);
echo "\n";
echo "seUrl : ".$seUrl;
echo "\n";
echo "searchUrl : ".$searchUrl;
echo "\n";
$msg = ob_get_clean();


// mail("jp@auvallon.ch",'crawlKeyword',$msg);


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

        $fp = fopen($file, "w");
        fputs($fp,$result_json);
        fclose($fp);

        $file_id = dirname( __FILE__ ).'/_perso_flat_file_id.php';
        if( file_exists($file_id) && filesize($file_id) > 0 ) {
        	include($file_id);
        }

    }

}




?>