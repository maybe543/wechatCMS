<?php
/**
 * 便利店模块处理程序
 *
 * @author Gorden
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Cyl_wxweizhangModuleProcessor extends WeModuleProcessor{
	private $tb_article = 'cyl_wxwenzhang_article';
    public function respond(){
    	global $_W, $_GPC;
        if(!$this->inContext) {
			$news = '请输入关键词搜索';	
			$this->beginContext();
			return $this->respText($news);	
			// 如果是按照规则触发到本模块, 那么先输出提示问题语句, 并启动上下文来锁定会话, 以保证下次回复依然执行到本模块
		} else {
			$content = $this->message['content'];
	        // 这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
	        $list = pdo_fetchall("SELECT id,title,thumb,pic,createtime,click,pcate,description FROM ".tablename($this->tb_article)." WHERE uniacid = '{$_W['uniacid']}' AND title LIKE '%{$content}%' ORDER BY id DESC");
	        
		    //var_dump($data->showapi_res_body->pagebean->contentlist);
		    $news = array();		    
	        foreach ($list as $key=>$item) {
	        	if ($key<=9) {
	        		$news[] = array(
	                'title' => $item['title'],
	                'description' => $item['description'],
	                'url' => $this->createMobileUrl('detail', array('id'=>$item['id'],'op'=>'detail')),
	                'picurl' => $item['thumb']
	           	 	);
	        	}	            
	        }
	        $this->endContext();	          	
	        
        }
        return $this->respNews($news);
    }
}