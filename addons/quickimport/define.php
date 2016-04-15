<?php

global $_W;
if (!defined('QUICKIMPORT_INC')) {
  define('QUICKIMPORT_INC', 1);
  define('MODULE_NAME', 'quickimport');
  define('APP_PHP', IA_ROOT . '/addons/quickimport/');
  define('MODULE_ROOT', IA_ROOT . '/addons/');
  define('APP_WEB', IA_ROOT . '/addons/quickimport/template/');
  define('APP_MOB', IA_ROOT . '/addons/quickimport/template/mobile/');
  define('ATTACH_DIR', IA_ROOT . '/attachment/');
  define('RES_CSS', $_W['siteroot'] . '/addons/quickimport/css/');
  define('RES_JS',  $_W['siteroot'] . '/addons/quickimport/js/');
  define('RES_IMG', $_W['siteroot'] . '/addons/quickimport/images/');

}
