<?php

/**
 * ============================================================================
 * 版权所有 2008-2012 多多科技，并保留所有权利。
 * 网站地址: http://soft.duoduo123.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/

class plugin {
	public $duoduo;
	public $plugin;
	public $plugin_code;
	public $_plugin;

	function __construct($duoduo,$plugin_code) {
		$this->duoduo = $duoduo;
		
		$plugin = $this->duoduo->select('plugin', '*', 'code="' . $plugin_code . '"');
		$this->plugin = $plugin;
		$this->_plugin = include(DDROOT.'/plugin/'.$plugin_code.'/set.php');
		
		if($this->_plugin['debug']==0 && $_GET['do']!='uninstall'){
			$re=check_plugin_auth($plugin['code'],$plugin['key']);
			if($re==1){
				dd_exit('不存在授权码，请进入后台百宝箱更新订单');
			}
			elseif($re==2){
				dd_exit('授权码无法解密，请进入后台百宝箱更新订单');
			}
			elseif($re==3){
				dd_exit('应用不存在或者已到期，请进入后台百宝箱购买');
			}
			elseif($re==4){
				dd_exit('授权码盗用，请进入后台百宝箱购买');
			}
		}
	}

	function check_status(){
		if($this->plugin['status']==0){
			error_html('应用已关闭');
		}
	}

	function install_mod_act($row) {
		$duoduo = $this->duoduo;
		$mod=$row['mod'];
		$act=$row['act'];
		$title=$row['title'];
		$tag=$row['tag'];
		$alias_mod_act_arr = dd_get_cache('alias');
		if (!isset ($alias_mod_act_arr[$mod . '/' . $act])) {
			$alias_mod_act_arr[$mod . '/' . $act] = array ($mod,$act);
			dd_set_cache('alias', $alias_mod_act_arr);
		}
		
		if($row['nav']==1){
			$nav_id = $duoduo->select('nav', 'id', '`mod`="' . $mod . '" and `act`="' . $act . '"');
			if (!$nav_id) {
				$data = array ('title' => $title,'sort' => 99,'mod' => $mod,'act' => $act,'tag' => $tag,'plugin'=>1,'addtime' => TIME);
				$duoduo->insert('nav', $data);
			}
		}
		
		$seo_id = $duoduo->select('seo', 'id', '`mod`="' . $mod . '" and `act`="' . $act . '"');
		if (!$seo_id) {
			$title=mysql_real_escape_string($title);
			$data = array ('title' => $title . ' - {WEBNAME}','mod' => $mod,'act' => $act,'keyword' => $title . ' - {WEBNAME}','desc' => $title . ' - {WEBNAME}','label' => '{WEBNAME}-网站名称','sys' => 0,'addtime' => TIME);
			$duoduo->insert('seo', $data);

			$title = template_parse(str_replace("\'", "'", $title . ' - {WEBNAME}'));
			$keyword = template_parse(str_replace("\'", "'", $title . ' - {WEBNAME}'));
			$desc = template_parse(str_replace("\'", "'", $title . ' - {WEBNAME}'));

			$page_title = '<title>' . $title . "</title> <!--网站标题-->\r\n";
			$page_title .= '<meta name="keywords" content="' . $keyword . '" />' . "\r\n";
			$page_title .= '<meta name="description" content="' . $desc . '" />' . "\r\n";
			$pagetag = $mod . '_' . $act;
			create_file(DDROOT . '/data/title/' . $pagetag . '.title.php', $page_title);
		}
		$page_tag = dd_get_cache('page_tag', 'array');
		$add_page_tag = 1;
		foreach ($page_tag as $row) {
			if ($row['mod'] == $mod && $row['act'] == $act && $row['tag'] == $tag) {
				$add_page_tag = 0;
				break;
			}
		}
		if ($add_page_tag == 1 && $tag != '') {
			$page_tag[] = array ('mod' => $mod,'act' => $act,'tag' => $tag);
			dd_set_cache('page_tag', $page_tag, 'array');
		}
	}

	function uninstall_mod_act($row) {
		$mod=$row['mod'];
		$act=$row['act'];
		$tag=$row['tag'];
		$alias_mod_act_arr = dd_get_cache('alias');
		if (isset ($alias_mod_act_arr[$mod . '/' . $act])) {
			unset ($alias_mod_act_arr[$mod . '/' . $act]);
			dd_set_cache('alias', $alias_mod_act_arr);
		}
		
		if($row['nav']==1){
			$nav_id = $this->duoduo->select('nav', 'id', '`mod`="' . $mod . '" and `act`="' . $act . '"');
			if ($nav_id > 0) {
				$this->duoduo->delete('nav', '`mod`="' . $mod . '" and `act`="' . $act . '"');
			}
		}
		
		$seo_id = $this->duoduo->select('seo', 'id', '`mod`="' . $mod . '" and `act`="' . $act . '"');
		if ($seo_id > 0) {
			$this->duoduo->delete('seo', '`mod`="' . $mod . '" and `act`="' . $act . '"');
			$pagetag = $mod . '_' . $act;
			unlink(DDROOT . '/data/title/' . $pagetag . '.title.php');
		}

		$page_tag = dd_get_cache('page_tag', 'array');
		$del_page_tag = 0;
		foreach ($page_tag as $k => $row) {
			if ($row['mod'] == $mod && $row['act'] == $act && $row['tag'] == $tag) {
				$del_page_tag = 1;
				break;
			}
		}
		if ($del_page_tag > 0 && $tag != '') {
			unset ($page_tag[$del_page_tag]);
			dd_set_cache('page_tag', $page_tag, 'array');
		}
	}
	
	function install_search(){
		$code=$this->plugin['code'];
		$row=$this->_plugin['search'];
		$a=dd_get_cache('plugin_nav_search');
		if(empty($a)) $a=array();
		$a[$code]=array('mod'=>$code,'act'=>$row['act'],'name'=>$row['search_name'],'value'=>$row['search_tip'],'width'=>$row['search_width'],'ver'=>2);
		dd_set_cache('plugin_nav_search',$a);
	}

	function uninstall_search(){
		$code=$this->plugin['code'];
		$a=dd_get_cache('plugin_nav_search');
		if(isset($a[$code])){
			unset($a[$code]);
			dd_set_cache('plugin_nav_search',$a);
		}
	}
	
	function update_info($type='install'){
		$url = DD_YUN_URL . '/index.php?m=Api&a=one&code=' . $this->plugin['code'] . '&domain=' . DOMAIN;
		$re = dd_get_json($url);
		if ($re['s'] != 0) {
			$data['title'] = $re['r']['title'];
			$data['addtime'] = $re['r']['addtime'];
			$data['authcode'] = $re['r']['authcode'];
			$data['endtime'] = $re['r']['endtime'];
			$data['admin_set'] = (int)$this->_plugin['admin_set'];
			$data['search_open'] = $re['r']['search_open'];
			$data['search_name'] = $re['r']['search_name'];
			$data['search_width'] = $re['r']['search_width'];
			$data['search_tip'] = $re['r']['search_tip'];
			$data['toper_name'] = $re['r']['username'];
			$data['toper_qq'] = $re['r']['qq'];
			$data['banben'] = $re['r']['banben'];
			$data['jiaocheng'] = $re['r']['jiaocheng'];
			$data['version'] = $re['r']['version'];
		}
		if($type=='install'){
			$data['install'] = 1;
		}
		$this->duoduo->update('plugin', $data, 'id="' . $this->plugin['id'] . '"');
	}

	function install() {
		$plugin = $this->plugin;
		$_plugin = $this->_plugin;

		if ($_plugin['need_include'] == 1) {
			$plugin_include = dd_get_cache('plugin_include');
			if (!in_array($plugin['code'], $plugin_include)) {
				$plugin_include[] = $plugin['code'];
			}
			dd_set_cache('plugin_include', $plugin_include);
		}
		
		if ($_plugin['rewrite'] == 1) {
			$plugin_rewrite = dd_get_cache('plugin_rewrite');
			if (!in_array($plugin['code'], $plugin_rewrite)) {
				$plugin_rewrite[] = $plugin['code'];
			}
			dd_set_cache('plugin_rewrite', $plugin_rewrite);
		}
		
		if ($_plugin['mingxi'] == 1) {
			$plugin_mingxi=include(DDROOT.'/plugin/'.$plugin['code'].'/mingxi.php');
			$install_mingxi=1;
			if(is_array($plugin_mingxi)){
				foreach($plugin_mingxi as $k=>$v){
					if(!preg_match('/^'.$plugin['code'].'/',$k)){
						$install_mingxi=0;
						break;
					}
				}
			}
			else{
				$install_mingxi=0;
			}
			
			if($install_mingxi==1){
				$mingxi=include(DDROOT.'/data/mingxi.php');
				foreach($plugin_mingxi as $k=>$v){
					$mingxi[$k]=$v;
				}
				dd_file_put(DDROOT.'/data/mingxi.php',"<?php\r\nreturn ".var_export($mingxi,1).";\r\n?>");
			}
		}
		
		foreach($this->_plugin['act_arr'] as $row){
			if(isset($row['mod']) && $row['mod']!=''){
				$row['mod']=$row['mod'];
			}
			else{
				$row['mod']=$this->plugin['code'];
			}
			$this->install_mod_act($row);
		}
		
		if(!empty($this->_plugin['search'])){
			$this->install_search();
		}
		
		foreach($this->_plugin['table'] as $table=>$field){
			$table='plugin_'.$table;
			$this->duoduo->delete_table($table); //先删除表
			$this->duoduo->creat_table($table,$field); //再创建表
		}
		
		$sql=str_replace('{%BIAOTOU%}',BIAOTOU,$this->_plugin['install_sql']);
		if ($sql != '') {
			$sql_arr = explode(';', $sql);
			foreach ($sql_arr as $sql) {
				$this->duoduo->query($sql);
			}
		}
		
		$this->update_info();
		
		$plugin_set = dd_get_cache('plugin');
		$plugin_set[$plugin['code']] = 1;
		dd_set_cache('plugin', $plugin_set);
	}

	function uninstall() {
		$plugin = $this->plugin;
		$_plugin = $this->_plugin;

		if ($_plugin['need_include'] == 1) {
			$plugin_include = dd_get_cache('plugin_include');
			foreach ($plugin_include as $k => $v) {
				if ($v == $plugin['code'])
					unset ($plugin_include[$k]);
			}
			dd_set_cache('plugin_include', $plugin_include);
		}
		
		if ($_plugin['rewrite'] == 1) {
			$plugin_rewrite = dd_get_cache('plugin_rewrite');
			foreach ($plugin_rewrite as $k => $v) {
				if ($v == $plugin['code'])
					unset ($plugin_rewrite[$k]);
			}
			dd_set_cache('plugin_rewrite', $plugin_rewrite);
		}
		
		if ($_plugin['mingxi'] == 1) {
			$mingxi=include(DDROOT.'/data/mingxi.php');
			foreach($mingxi as $k=>$v){
				if(preg_match('/^'.$plugin['code'].'/',$k)){
					unset($mingxi[$k]);
				}
			}
			dd_file_put(DDROOT.'/data/mingxi.php',"<?php\r\nreturn ".var_export($mingxi,1).";\r\n?>");
		}

		$plugin_set = dd_get_cache('plugin');
		unset ($plugin_set[$plugin['code']]);
		dd_set_cache('plugin', $plugin_set);

		foreach($this->_plugin['act_arr'] as $row){
			if(isset($row['mod']) && $row['mod']!=''){
				$row['mod']=$row['mod'];
			}
			else{
				$row['mod']=$this->plugin['code'];
			}
			$this->uninstall_mod_act($row);
		}
		
		if(!empty($this->_plugin['search'])){
			$this->uninstall_search();
		}
		
		foreach($this->_plugin['table'] as $table=>$field){
			$table='plugin_'.$table;
			$this->duoduo->delete_table($table); //先删除表
		}
		
		$sql=str_replace('{%BIAOTOU%}',BIAOTOU,$this->_plugin['uninstall_sql']);
		if ($sql != '') {
			$sql_arr = explode(';', $sql);
			foreach ($sql_arr as $sql) {
				$this->duoduo->query($sql);
			}
		}
		
		$data['install'] = 0;
		$data['status'] = 0;
		$this->duoduo->update('plugin', $data, 'id="' . $this->plugin['id'] . '"');
		
		$this->duoduo->delete('plugin_file','code="'.$this->plugin['code'].'"');
		
		unlink(DDROOT.'/data/json/plugin/'.$this->plugin['code'].'.php');
	}
}
?>