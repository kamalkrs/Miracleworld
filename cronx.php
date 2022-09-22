<?php
date_default_timezone_set('Asia/Kolkata');
$h = date("H");
//if ($h == 1) {
    $url = 'https://dainfotech.org/home/daily_roi';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);

    $url = 'https://dainfotech.org/home/matching_income';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
//}
