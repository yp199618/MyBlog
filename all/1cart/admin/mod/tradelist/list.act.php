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

if(!defined('ADMIN')){
	exit('Access Denied');
}

if(isset($_GET['mini'])){
	$page = !($_GET['page'])?'1':intval($_GET['page']);
	$pagesize=1000;
	$frmnum=($page-1)*$pagesize;
	
	$a=$duoduo->select_all(MOD,'id,trade_id','1 order by id asc limit '.$frmnum.','.$pagesize);
	if(empty($a)){
		jump(u(MOD,ACT),'提取完毕');
	}
	
	foreach($a as $row){
		if(strpos($row['trade_id'],'_')>0){
			$trade_id=preg_replace('/_\d+$/','',$row['trade_id']);
		}
		else{
			$trade_id=$row['trade_id'];
		}
		$data['mini_trade_id']=substr($trade_id,0,8).substr($trade_id,-4,4);
		
		if($data['mini_trade_id']==0 || $data['mini_trade_id']==''){
			$data['mini_trade_id']=111111111111;
		}
		$duoduo->update(MOD,$data,'id="'.$row['id'].'"');
	}
	
	$page++;
	PutInfo('提取mini订单号。。。<br/><br/><img src="../images/wait2.gif" />',u(MOD,ACT,array('mini'=>1,'page'=>$page)));
}


$select_arr=array('trade_id'=>'订单号','item_title'=>'商品名','uname'=>'会员名','uid'=>'会员id');
$select2_arr=array('0'=>'全部','1'=>'电脑','2'=>'手机');

$page = !($_GET['page'])?'1':intval($_GET['page']);
$pagesize=20;
$frmnum=($page-1)*$pagesize;
$q=$_GET['q'];
$se=$_GET['se'];
$status=$_GET['status'];
$se2=$_GET['se2'];
$checked=$_GET['checked'];

$page_arr=array('q'=>$q,'se'=>$se,'se2'=>$se2,'checked'=>$checked,'status'=>$status);

if($checked==''){
    unset($checked);
}

if(!isset($checked)){
    $where=' ';
}
else{
    $where=' a.checked="'.$checked.'" and ';
}

if(!array_key_exists($se,$select_arr)){
    $se='trade_id';
}

if($se=='uname'){
    $uid=$duoduo->select('user','id','ddusername="'.$q.'"');
	$where.='a.uid="'.$uid.'"';
}
else{
    $where.='a.`'.$se.'` like "%'.$q.'%"';
}
$status=gbk2utf8($status);
if($status){
	$where.=" and a.`status`=".$status;
	$page_arr['status']=$status;
}
if(isset($se2) && $se2>0){
	$where.=' and a.`platform`='.$se2;
	$page_arr['se2']=$se2;
}

$stime=$_GET['stime'];
$dtime=$_GET['dtime'];
if(isset($stime) && isset($dtime)){
	$where.=' and pay_time >= "'.$stime.'" and pay_time < "'.$dtime.'"';
	$page_arr['stime']=$stime;
	$page_arr['dtime']=$dtime;
}

if(TAOTYPE==1){
	$order_by='create_time';
}
else{
	$order_by='pay_time';
}

$total=$duoduo->count(MOD.' as a',$where);
$row=$duoduo->left_join(MOD.' as a','user AS b ON a.uid = b.id','a.*,b.ddusername as uname',$where.' order by a.'.$order_by.' desc,id desc limit '.$frmnum.','.$pagesize);

if(TAOTYPE==1){
    $_act='import';
    $_act_name='导入订单';
	if($total>0){
		$a=$duoduo->select(MOD,'mini_trade_id','1 order by id asc');
		$b=$duoduo->select(MOD,'mini_trade_id','1 order by id desc');
		if(strlen($a)==8 || strlen($a)==9 || strlen($b)==8 || strlen($b)==9){
			echo script("alert('系统升级，需从新提取mini订单号！')");
			$tiqu_mini=1;
		}
		if(strlen($a)==0 || strlen($b)==0){
			echo script("alert('无初级包模式需要提取mini订单号！')");
			$tiqu_mini=1;
		}
	}
	$order_by='create_time';
}
else{
    $_act='report';
    $_act_name='获取订单';
	$tiqu_mini=0;
	$order_by='pay_time';
}
?>