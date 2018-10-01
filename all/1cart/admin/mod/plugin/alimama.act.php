<?php
/**
 * ============================================================================
 * 版权所有 2008-2012 多多科技，并保留所有权利。
网站地址: http://soft.duoduo123.com；
----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
使用；不允许对程序代码以任何形式任何目的的再发布。
============================================================================
 */

if (!defined('ADMIN')) {
	exit('Access Denied');
}

$alimama_class=fs('alimama');

$alimama_data=$webset['alimama'];

if(isset($_GET['test'])){
	if($alimama_data['username']=='' || $alimama_data['password']==''){
		jump(-1,'淘宝帐号必填');
	}
	$alimama_class->set_name_pwd($alimama_data['username'],$alimama_data['password']);

	$excel=$alimama_class->get_excel();
	if($alimama_class->error==1){
		jump(-1,$excel['r']);
	}
	include DDROOT . '/comm/readxls.php';
	$data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('utf-8');
	$data->read($excel,2);
	
	$re=$alimama_class->check_cookie();
	if($re==0){
		jump(-1,'cookie获取错误');
	}
	
	$c=count($data->sheets[0]['cells'])-1;
	jump(-1,'可以正常使用，今日订单'.$c.'条！');
}

if ($_POST['sub'] != '') {
	if($_POST['username']==''){
		jump(-1,'淘宝帐号不能为空');
	}
	unset($_POST['sub']);
	$duoduo->set_webset('alimama', $_POST);
	jump(-1,'设置完成');
}
else{
	if($webset['alimama']['username']!=''){
		$url=DD_YUN_URL."/index.php?&g=Home&m=Alimama&a=index&cha=1&alimama=".urlencode($alimama_data['username'])."&url=".urlencode(SITEURL);
		$a=dd_get_json($url);
		if($a['s']==1){
			$yun_alimama['buy']=1;
		}
		else{
			$yun_alimama['buy']=0;
			$yun_alimama['t']=$a['t'];
		}
	}
	else{
		$yun_alimama['buy']=0;
	}
}

if(function_exists('curl_exec')){
	$c=fs('collect');
	$c->set_func='curl';
	$c->target_charset='gbk';
	$c->get('http://u.alimama.com/membersvc/index.htm');
	if(strpos($c->val,'淘宝联盟')!==false){
		$curl_ok=1;
	}
	else{
		$curl_ok=0;
	}
}
else{
	$curl_ok=0;
}
?>