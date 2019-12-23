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

        // si le captcha de Google apparait
        if( strlen($result_json) > 4000 ) {

            $fp = fopen($file, "w");
            fputs($fp, $result_json);
            fclose($fp);

        }

    }

}

?>