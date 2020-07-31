<?php
/***************************************************************************
 *   Copyright (C) 2009-2011 by Geo Varghese(www.seofreetools.net)  	   *
 *   sendtogeo@gmail.com   												   *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
 ***************************************************************************/



?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<meta http-equiv="Refresh" content="120;URL=<?php echo $_SERVER["PHP_SELF"] ?>">
</head>
<body>
<pre>
    <?php

    ob_start();

    # the section for generate reports using system cron job
    include_once("includes/sp-load.php");
    include_once(SP_CTRLPATH."/cron.ctrl.php");
    include_once(SP_CTRLPATH."/report.ctrl.php");
    include_once(SP_CTRLPATH."/searchengine.ctrl.php");
    include_once(SP_CTRLPATH."/keyword.ctrl.php");
    include_once(SP_CTRLPATH."/moz.ctrl.php");
    include_once(SP_CTRLPATH."/webmaster.ctrl.php");

    include_once(SP_CTRLPATH."/social_media.ctrl.php");
    include_once(SP_CTRLPATH."/review_manager.ctrl.php");
    include_once(SP_CTRLPATH."/analytics.ctrl.php");
    include_once(SP_CTRLPATH."/information.ctrl.php");

    $controller = New CronController();
    $controller->timeStamp = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

    $includeList = array();
    $userList = array();

    // check whether user is passed with script as argument
    if (!empty($argv[1])) {
        $userId = intval($argv[1]);
        $userCtrler = new UserController();
        $userInfo = $userCtrler->__getUserInfo($userId);
        $userList[] = $userInfo;

        // check whether seo tools id passed with the script as second argument
        if (!empty($argv[2])) {
            $includeList[] = intval($argv[2]);
        }
    }

    // call cronjob function
    echo "\n\n=== Cron job execution started on - " . date("Y-m-d H:i:s") . " ===\n";

    // sync search engines
    $seCtrler = new SearchEngineController();
    $ret_sync = $seCtrler->doSyncSearchEngines(true, true);
    echo $ret_sync['result'] . "\n";

    // check system alerts
    $alertCtrler = new AlertController();
    $ret_sync = $alertCtrler->updateSystemAlerts();
    echo $ret_sync['result'] . "\n";

    $controller->executeCron($includeList, $userList);
    echo "\n=== Cron job execution completed on - " . date("Y-m-d H:i:s") . " ===\n\n";

    // delete crawl logs before 2 months
    include_once(SP_CTRLPATH."/crawllog.ctrl.php");
    $crawlLog = new CrawlLogController();
    $crawlLog->clearCrawlLog(SP_CRAWL_LOG_CLEAR_TIME);

    echo "Clearing crawl logs before " . SP_CRAWL_LOG_CLEAR_TIME . " days\n";
    $crawlLog->clearMaillLog(SP_CRAWL_LOG_CLEAR_TIME);
    echo "Clearing mail logs before " . SP_CRAWL_LOG_CLEAR_TIME . " days\n";

    $message = ob_get_clean();

    echo $message;

    ?>
</pre>
</body>
</html>