<?php
defined('IN_IA') or exit('Access Denied');

function env($key, $default = null)
{
    $value = getenv($key);
    if ($value === false) {
        return $default;
    }
    return $value;
}
/**
 * 获取环境变量
 * @param $key
 * @param null $default
 * @return null|string
 */
$serverName =   env("MYSQL_PORT_3306_TCP_ADDR", "localhost");
$databaseName = env("MYSQL_INSTANCE_NAME", "homestead");
$username =     env("MYSQL_USERNAME", "homestead");
$password =     env("MYSQL_PASSWORD", "secret");



$config = array();

$config['db']['host'] = $serverName;
$config['db']['username'] = $username;
$config['db']['password'] = $password;
$config['db']['port'] = '3306';
$config['db']['database'] = $databaseName;
$config['db']['charset'] = 'utf8';
$config['db']['pconnect'] = 0;
$config['db']['tablepre'] = 'ims_';

// --------------------------  CONFIG COOKIE  --------------------------- //
$config['cookie']['pre'] = '671f_';
$config['cookie']['domain'] = '';
$config['cookie']['path'] = '/';

// --------------------------  CONFIG SETTING  --------------------------- //
$config['setting']['charset'] = 'utf-8';
$config['setting']['cache'] = 'mysql';
$config['setting']['timezone'] = 'Asia/Shanghai';
$config['setting']['memory_limit'] = '256M';
$config['setting']['filemode'] = 0644;
$config['setting']['authkey'] = '6ae17bf5';
$config['setting']['founder'] = '1';
$config['setting']['development'] = 0;
$config['setting']['referrer'] = 0;

// --------------------------  CONFIG UPLOAD  --------------------------- //
$config['upload']['image']['extentions'] = array('gif', 'jpg', 'jpeg', 'png');
$config['upload']['image']['limit'] = 5000;
$config['upload']['attachdir'] = 'attachment';
$config['upload']['audio']['extentions'] = array('mp3');
$config['upload']['audio']['limit'] = 5000;