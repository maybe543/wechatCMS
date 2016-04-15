<?php
/**
 * 附近商店模块处理程序
 *
 * @author fwei.net
 * @url http://www.fwei.net/
 */
defined('IN_IA') or exit('Access Denied');

class Fwei_nearshopModuleProcessor extends WeModuleProcessor {
	
	public $tablename = 'fwei_nearshop';
	
	public function respond() {
		global $_W;
		$weid = $_W['uniacid'];
		//return $this->respText( print_r( $this->message, true ) );
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
		if( $this->message['msgtype'] == 'location' ){
			$config = $this->module['config'];
			$range = ($config['range'] ? $config['range'] : 5) * 1000;
			$emptyMsg = $config['msg'] ? $config['msg'] : '抱歉，您周边没有我们的商户！';

			$locationX = $this->message['location_x'];
			$locationY = $this->message['location_y'];
			$item = $where = '';
			if( $locationX && $locationY ){
				$juli = "round(6378.138*2*asin(sqrt(pow(sin(
				({$locationY}*pi()/180-lng*pi()/180)/2),2)+cos({$locationY}*pi()/180)*cos(lng*pi()/180)*
				pow(sin( ({$locationX}*pi()/180-lat*pi()/180)/2),2)))*1000)";
				$item = ",{$juli} AS juli";
				$where = " AND {$juli}<={$range}";
			}
			$sql = "SELECT * {$item} FROM ".tablename($this->tablename)." WHERE weid='{$weid}' {$where} ORDER BY juli ASC LIMIT 10";
			$list = pdo_fetchall($sql);
			$news = array();
			foreach ($list as $row){
				$news[] = array(
					'title'	=>	$row['title'] . " (距您{$row['juli']}米)",
					'description'	=>	$row['content'],
					'picurl'	=>	tomedia($row['thumb']),
					'url'	=>	$row['outlink']? $row['outlink'] : $this->createMobileUrl('detail', array('id'=>$row['id'])),
				);
			}
			if( empty($news) ){
				return $this->respText( $emptyMsg );
			} else {
				return $this->respNews( $news );
			}
		} else {
			
			return $this->respText( '请先发送位置给我！点击底部\'+\'号，再选择\'位置\',待地图显示出来后，点击发送' );
		}
		
	}
}