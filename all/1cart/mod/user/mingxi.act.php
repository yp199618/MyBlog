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

if(!defined('INDEX')){
	exit('Access Denied');
}
/**
* @name 用户明细
* @copyright duoduo123.com
* @example 示例user_mingxi();
* @param $pagesize 每页多少条
* @return $parameter 结果集合
*/
function act_user_mingxi($pagesize=10,$field='*',$field2='*'){
	global $duoduo;
	$webset = $duoduo->webset;
	$dduser = $duoduo->dduser;
	$tx_tool=include(DDROOT.'/data/tx_tool.php');
	$mingxi_tpl=include(DDROOT.'/data/mingxi.php'); //明细结构数据
	$keyword=$_GET["keyword"]; 
	$do=empty($_GET['do'])?'in':$_GET['do'];
	$page = !($_GET['page'])?'1':intval($_GET['page']);
	
	$page2=($page-1)*$pagesize;
	
	if($do=='in'){
		$total = $duoduo->count('mingxi',"uid='".$dduser['id']."'");	
		$mingxi=$duoduo->select_all('mingxi',$field,"uid='".$dduser['id']."' and (jifen>0 or money>0 or jifenbao>0) order by addtime desc limit $page2,$pagesize");
	}
	elseif($do=='out'){
		$total = $duoduo->count('tixian',"uid='".$dduser['id']."'");	
		$mingxi=$duoduo->select_all('tixian',$field2,"uid='".$dduser['id']."' order by id desc limit $page2,$pagesize");
		$tixian_arr=array(0=>'<span style="color:#ff3300">提现待审核</span>',1=>'<span style="color:#009900">提现成功</span>',2=>'<span style="color:#333333">提现失败</span>');
	}
	elseif($do=='tui'){
		$total = $duoduo->count('mingxi',"uid='".$dduser['id']."' and shijian in (13,15)");	
		$mingxi=$duoduo->select_all('mingxi',$field,"uid='".$dduser['id']."' and shijian in (13,15) order by id desc limit $page2,$pagesize");
	}
	elseif($do=='kou'){
		$total = $duoduo->count('mingxi',"uid='".$dduser['id']."' and shijian in (21)");	
		$mingxi=$duoduo->select_all('mingxi',$field,"uid='".$dduser['id']."' and shijian in (21) order by id desc limit $page2,$pagesize");
	}
	unset($duoduo);
	$parameter['tx_tool']=$tx_tool;
	$parameter['mingxi_tpl']=$mingxi_tpl;
	$parameter['keyword']=$keyword;
	$parameter['do']=$do;
	$parameter['page']=$page;
	$parameter['page2']=$page2;
	$parameter['total']=$total;
	$parameter['pagesize']=$pagesize;
	$parameter['mingxi']=$mingxi;
	$parameter['tixian_arr']=$tixian_arr;
	return $parameter;
}
?>