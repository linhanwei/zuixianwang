<?php
session_start();
define('APP_ID','store');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
if (!@include(dirname(dirname(__FILE__)).'/global.php')) exit('global.php isn\'t exists!');
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');
if (!@include(BASE_CORE_PATH.'/site.php')) exit('site.php isn\'t exists!');
define('APP_SITE_URL',STORE_SITE_URL);
define('TPL_NAME','default');
define('MALL_RESOURCE_SITE_URL',STORE_SITE_URL.DS.'resource');
define('MALL_TEMPLATES_URL',STORE_SITE_URL.'/templates/'.TPL_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);

Base::run();