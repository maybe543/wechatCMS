<?php
global $_W,$_GPC;


$url = murl('activity',array('a'=>'token','do'=>'mine'));
header("location:$url");
exit();