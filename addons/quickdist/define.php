<?php
 global $_W;
if (!defined('QUICKDIST_INC')){
    define('QUICKDIST_INC', 1);
    define('MODULE_NAME', 'quickdist');
    define('MODULE_ROOT', IA_ROOT . '/addons/');
    define('APP_PHP', IA_ROOT . '/addons/quickdist/');
    define('APP_WEB', IA_ROOT . '/addons/quickdist/template/');
    define('APP_MOB', IA_ROOT . '/addons/quickdist/template/mobile/');
    define('ATTACH_DIR', IA_ROOT . '/resource/attachment/');
    define('RES_CSS', $_W['siteroot'] . '../addons/quickdist/css/');
    define('RES_JS', $_W['siteroot'] . '../addons/quickdist/js/');
    define('RES_IMG', $_W['siteroot'] . '../addons/quickdist/images/');
}
