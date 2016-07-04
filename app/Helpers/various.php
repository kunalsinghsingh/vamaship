<?php
define('SITE_URL', '');
//define('SITE_URL', 'http://concrete-crm.cruxservers.in/');


function ConvertGMTToLocalTimezone($gmttime, $timezoneRequired) {
    $system_timezone = date_default_timezone_get();

    date_default_timezone_set("GMT");
    $gmt = date("Y-m-d h:i:s A");

    $local_timezone = $timezoneRequired;
    date_default_timezone_set($local_timezone);
    $local = date("Y-m-d h:i:s A");

    date_default_timezone_set($system_timezone);
    $diff = (strtotime($local) - strtotime($gmt));

    $date = new DateTime($gmttime);
    $date->modify("+$diff seconds");
    $timestamp = $date->format("d-m-Y H:i:s");
    return $timestamp;
}

?>