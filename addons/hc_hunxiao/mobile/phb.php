<?php
	$month = date('m', strtotime("-1 month"));
	//上一个月第一天的时间戳
	$premonth = strtotime(date('Y-m-1 00:00:00', strtotime("-1 month")));
	$temptime = date('Y-m-1 00:00:00', strtotime("-1 month"));
	//上一个月最后一天的时间戳
	$premonthed = strtotime(date('Y-m-d 23:59:59', strtotime("$temptime +1 month -1 day")));
	
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$commission = pdo_fetchall("select sum(c.commission) as commission, m.realname, m.mobile from ".tablename('hc_hunxiao_commission')." as c left join ".tablename('hc_hunxiao_member')." as m on c.weid = m.weid and c.mid = m.id where c.flag = 0 and m.realname !='' and c.weid = ".$weid." and c.createtime >= ".$premonth." and c.createtime <= ".$premonthed." group by c.mid order by sum(c.commission) desc, c.createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$total = pdo_fetchcolumn("select count(distinct c.mid) from ".tablename('hc_hunxiao_commission')." as c left join ".tablename('hc_hunxiao_member')." as m on c.weid = m.weid and c.mid = m.id where c.flag = 0 and c.weid = ".$weid." and m.realname !='' and c.createtime >= ".$premonth." and c.createtime <= ".$premonthed);
	$pager = pagination1($total, $pindex, $psize);
	
	include $this->template('phb');
?>