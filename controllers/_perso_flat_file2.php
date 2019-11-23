<?php

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

    }

}

?>