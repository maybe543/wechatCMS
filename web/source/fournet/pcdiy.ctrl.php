<?php
/**
 * [Weizan System] Copyright (c) 2014 012WZ.COM
 * Weizan is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
$do = in_array($do, array('display', 'post', 'delete')) ? $do : 'display';

if($do == 'display') {
} elseif($do == 'post') {
} elseif($do == 'delete') {
}
template('fournet/pcdiy');

