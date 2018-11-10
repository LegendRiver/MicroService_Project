<?php
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'common_moudles/vendor/autoload.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'third_lib/facebook_sdk/vendor/autoload.php';

initTimeZone();

function initTimeZone()
{
    date_default_timezone_set("Asia/Shanghai");
}
