<?php
function admin_url($file = '', $redirect = false)
{
    $url = site_url('admin');
    if ($file <> '') {
        $url .= '/' . $file;
    }
    if ($redirect) {
        $cur = urlencode(current_url());
        if (count($_GET) > 0)
            $cur .= '?' . http_build_query($_GET);
        $url .= '?redirect_to=' . $cur;
    }
    return $url;
}

function api_url($slug = '')
{
    if ($slug != '') {
        $slug .= "/?";
    }
    $url = site_url('api/call/' . $slug);
    return $url;
}

function admin_view($view = '')
{
    $config = config('AppConfig');
    $path = $config->admin_view_folder;
    if ($view != '') {
        $path = $path . '/' . $view;
    }
    return $path;
}

function inr_rs($amt)
{
    return ' <i class="fa fa-inr"></i> ' . number_format($amt, 2);
}
function usd_rs($amt)
{
    return number_format($amt, 2, '.', '');
}

function upload_dir($file = '')
{
    $config = config('AppConfig');
    $f = $config->upload_dir;
    return $f . '/' . $file;
}

function getDayName($id)
{
    $arr = array(1 => 'Sun', 2 => 'Mon', 3 => 'Tue', 4 => 'Wed', 5 => 'Thu', 6 => 'Fri', 7 => 'Sat');
    return $arr[$id];
}

function getDayIndex($name)
{

    $arr = array(1 => 'Sun', 2 => 'Mon', 3 => 'Tue', 4 => 'Wed', 5 => 'Thu', 6 => 'Fri', 7 => 'Sat');
    $id = 0;
    foreach ($arr as $index => $d) {
        if ($name == $d) {
            $id = $index;
        }
    }
    return $id;
}

function is_login()
{
    if (isset($_SESSION['login']) && isset($_SESSION['login']['user_id'])) {
        return true;
    } else {
        return false;
    }
}

function is_shop()
{
    if (isset($_SESSION['login']) && $_SESSION['login']['is_shop'] == true) {
        return true;
    } else {
        return false;
    }
}

function is_admin_login()
{
    if (isset($_SESSION['userid']) && $_SESSION['userid'] > 0) {
        return true;
    } else {
        return false;
    }
}


function id2userid($id)
{
    $config = config("AppConfig");
    $prefix = $config->user_prefix;
    $sid = $prefix .  str_pad($id, 4, '0', STR_PAD_LEFT);
    return $sid;
}

function userid2id($sid)
{
    $config = config("AppConfig");
    $prefix = $config->user_prefix;
    $sid = substr($sid, strlen($prefix));
    return intval($sid);
}


function user_id()
{
    $sesssion = session();
    return $_SESSION['login']['user_id'];
}



function is_home()
{
    if (current_url() == site_url()) {
        return true;
    } else {
        return false;
    }
}

function human_timing($time)
{
    $time = strtotime($time);
    $time = time() - $time; // to get the time since that moment
    $tokens = array(
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    foreach ($tokens as $unit => $text) {
        if ($time < $unit)
            continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ' ago';
    }
}

function noOfDays($oldDate)
{
    $dt = date("Y-m-d", strtotime($oldDate));
    $d1 = new DateTime($dt);
    $d2 = new DateTime();

    $d3 = $d2->diff($d1);
    return $d3->days * ($d3->invert == 0 ? 1 : -1);
}


function theme_url($file = '')
{
    return base_url('public/themes/' . $file);
}

function amount_format($amout, $format = false)
{
    return number_format($amout, '2', '');
}

require "Paykassa.php";
