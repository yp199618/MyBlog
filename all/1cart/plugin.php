<?php
/**
 * ============================================================================
 * 版权所有 2008-2013 多多科技，并保留所有权利。
 * 网站地址: http://soft.duoduo123.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
define('INDEX',1);
define('PLUGIN',1);

include ('comm/dd.config.php');
include (DDROOT.'/comm/checkpostandget.php');

if($webset['gzip']==1){ //gzip输出
	ob_start('ob_gzip');
}

if($webset['webclose']==1){
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><title>".WEBNICK."网站关闭提示</title><table width=\"550\" height=\"176\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"border:1px dotted #ddd\"><tr><td colspan=\"2\" align=\"center\"><img src=\"images/alert.gif\" width=\"100\" height=\"90\" /></td><td width=\"370\" style=\"font-size:25px; font-weight:bold;\">".$webset['webclosemsg']."</td></tr></table><table width=\"550\" height=\"25\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"><tr><td colspan=\"3\" align=\"right\">- by ".WEBNICK."</td></tr></table>";
	exit;
}

include (DDROOT . '/comm/Taoapi.php');
include (DDROOT . '/comm/ddTaoapi.class.php');
include (DDROOT . '/mod/header.act.php');

$alias_mod_act_arr=dd_get_cache('alias');

define('TPLPATH',DDROOT.'/template/'.MOBAN);
define('TPLURL','template/'.MOBAN);

define('PLUGIN_TPLPATH',DDROOT.'/plugin/'.MOD.'/template/');
define('PLUGIN_TPLURL','plugin/'.MOD.'/template/');

$ddTaoapi = new ddTaoapi();
if(!empty($dduser)){
    $ddTaoapi->dduser=$dduser;
}

$mod_name=mod_name($mod,$act);

if(browser()!=''){ //判断浏览器，节省淘宝api
    define('BROWSER',1);
}
else{
    define('BROWSER',0);
}

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	define('AJAX',1);
}
else{
	define('AJAX',0);
}

if(WJT==1 && isset($_GET['q'])){
	if(is_url($_GET['q'])==0){
		$_GET['q']=gbk2utf8($_GET['q']);
	}
}

$page_tag=dd_get_cache('page_tag');
if(isset($page_tag[MOD.'/'.ACT])){
	define('PAGETAG',$page_tag[MOD.'/'.ACT]);
}
else{
	define('PAGETAG',MOD);
}

$plugin_id=(int)$duoduo->select('plugin','id','code="'.MOD.'"');
if($plugin_id==0){
	error_html('应用不存在');
}

//引入插件处理文件
include(DDROOT.'/comm/plugin.class.php');

$plugin_config=dd_get_cache('plugin/'.MOD);
if(empty($plugin_config)){
	error_html('应用未开启');
}
elseif($plugin_config['status']!=1){
	error_html('应用已关闭');
}

if(file_exists(DDROOT . '/plugin/'.MOD.'/mod/fun.php')){
	include(DDROOT . '/plugin/'.MOD.'/mod/fun.php'); //引入插件公共函数库
}

$act_file=DDROOT.'/plugin/'.MOD.'/mod/'.ACT.'.act.php';
if(file_exists($act_file)){
	include($act_file); //引入插件模块
}

if(ACT!='ajax'){
	$tpl_file=DDROOT.'/plugin/'.MOD.'/template/'.ACT.'.tpl.php';
	if(file_exists($tpl_file)){
		include($tpl_file); //引入插件模板
		include(DDROOT.'/comm/cron.php'); //计划任务
	}
}
$duoduo->close();
unset ($duoduo);
unset ($ddTaoapi);
unset ($webset);
?>