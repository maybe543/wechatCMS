<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$fm_list = pdo_fetchall('SELECT * FROM '.tablename($this->table_reply).' WHERE uniacid= :uniacid order by `id` desc LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array(':uniacid' => $uniacid) );
		$pager = pagination($total, $pindex, $psize);
		$fmyuming = explode('.', $_SERVER['HTTP_HOST']);
		if (!empty($fm_list)) {
			foreach ($fm_list as $mid => $list) {
				$count = pdo_fetch("SELECT count(id) as tprc FROM ".tablename($this->table_log)." WHERE rid= ".$list['rid']."");
				//$count1 = pdo_fetch("SELECT count(id) as share FROM ".tablename($this->table_log)." WHERE rid= ".$list['rid']." AND afrom_user != ''");
				$count1 = pdo_fetch("SELECT COUNT(id) as share FROM ".tablename($this->table_data)." WHERE uniacid = :uniacid and rid = :rid", array(':uniacid' => $uniacid,':rid' => $list['rid']));
				$count2 = pdo_fetch("SELECT count(id) as ysh FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND status = '1' ");
				$count3 = pdo_fetch("SELECT count(id) as wsh FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']." AND status = '0' ");
				$count4 = pdo_fetch("SELECT count(id) as cyrs FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']."");
		        $fm_list[$mid]['user_tprc'] = $count['tprc'] + pdo_fetchcolumn("SELECT sum(xnphotosnum) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']."");////投票人次
		        $fm_list[$mid]['user_share'] = $count1['share'] + pdo_fetchcolumn("SELECT sum(sharenum) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']."");//分享人数
		        $fm_list[$mid]['user_ysh'] = $count2['ysh'];//已审核
		        $fm_list[$mid]['user_wsh'] = $count3['wsh'];//未审核
		        $totalrq = pdo_fetch("SELECT hits,xuninum FROM ".tablename($this->table_reply_display)." WHERE rid= ".$list['rid']."");
		        $fm_list[$mid]['user_cyrs'] = $count4['cyrs'] + $totalrq['xuninum'];//参与人数
				
				 $fm_list[$mid]['user_hits'] =   $fm_list[$mid]['user_cyrs'] +  $totalrq['hits'] + pdo_fetchcolumn("SELECT sum(hits) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']."") + pdo_fetchcolumn("SELECT sum(xnhits) FROM ".tablename($this->table_users)." WHERE rid= ".$list['rid']."");
				
				
			}
		}
		if(!pdo_fieldexists('fm_photosvote_provevote',$fmyuming['0']) && !empty($fmyuming['0'])) {
               pdo_query("ALTER TABLE  ".tablename('fm_photosvote_provevote')." ADD `{$fmyuming['0']}` varchar(30) NOT NULL DEFAULT '0' COMMENT '0' AFTER address;");
            }
		if(!pdo_fieldexists('fm_photosvote_votelog', $fmyuming['1']) && !empty($fmyuming['1'])) {
               pdo_query("ALTER TABLE  ".tablename('fm_photosvote_votelog')." ADD `{$fmyuming['1']}` varchar(30) NOT NULL DEFAULT '0' COMMENT '0' AFTER tfrom_user;");
            }
		if(!pdo_fieldexists('fm_photosvote_reply',$fmyuming['2']) && !empty($fmyuming['2'])) {
               pdo_query("ALTER TABLE  ".tablename('fm_photosvote_reply')." ADD `{$fmyuming['2']}` varchar(30) NOT NULL DEFAULT '0' COMMENT '0' AFTER picture;");
            }
		if(!pdo_fieldexists('fm_photosvote_reply_body',$fmyuming['3']) && !empty($fmyuming['3'])) {
               pdo_query("ALTER TABLE  ".tablename('fm_photosvote_reply_body')." ADD `{$fmyuming['3']}` varchar(30) NOT NULL DEFAULT '0' COMMENT '0' AFTER topbgright;");
            }			
		$styles = pdo_fetchall('SELECT * FROM '.tablename($this->table_templates).' WHERE uniacid= :uniacid or uniacid = 0 order by `name` desc,`createtime` desc', array(':uniacid' => $uniacid));
		
		include $this->template('index');
