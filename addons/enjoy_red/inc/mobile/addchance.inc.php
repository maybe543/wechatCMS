<?php
global $_W, $_GPC;
$uniacid      = $_W['uniacid'];
$openid       = $_GPC['openid'];
//加机会
$share_chance = pdo_fetchcolumn("select share_chance from " . tablename('enjoy_red_reply') . " where uniacid=" . $uniacid . "");
pdo_query("update " . tablename('enjoy_red_chance') . " set chance=chance+" . $share_chance . " where uniacid=" . $uniacid . " and openid='" . $openid . "'");