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
if (!defined('ADMIN')) {
    exit('Access Denied');
}

if ($_POST['sub'] != '' && TAOTYPE==1) {
    $id = empty($_POST['id']) ? 0 : (int)$_POST['id'];
    if ($id > 0) {
        $do = (int)$_POST['do'];
		$post=$_POST;
		unset($post['id']);
		unset($post['do']);
		unset($post['sub']);
		unset($post['uname']);
		unset($post['status']);
		unset($post['caozuo']);
		unset($post['shuoming']);
		
		if($post['pay_time']==''){unset($post['pay_time']);}

		$duoduo->update(MOD,$post,'id=' . $id);
        $tradelist_data = $duoduo->select('tradelist', '*', 'id=' . $id);
		
		//求关联订单的id
		$ids_arr[]=$id;
		if($tradelist_data['guanlian']!=''){
			$a=explode(',',$tradelist_data['guanlian']);
			$ids_arr=array_merge($ids_arr,$a);
		}
		else{
			if(strpos($tradelist_data['trade_id'],'_')!==false){
				$a=preg_replace('/_\d{1,2}$/','',$tradelist_data['trade_id']);
				$a=$duoduo->select('tradelist','id,guanlian','trade_id="'.$a.'"');
				if($a['guanlian']!=''){
					$b=explode(',',$a['guanlian']);
					$ids_arr=array_merge(array($a['id']),$b);
				}
			}
		}
		//$ids_arr=$duoduo->select_all('tradelist','id','mini_trade_id="'.substr($tradelist_data['trade_id'],0,8).'"');  //另一种算法，如果订单很多，效率上会慢一点

        if (empty($tradelist_data['uid'])) {
            if ($_POST['uname'] == '') {
				//提交有订单状态的时候，没有写会员直接修改状态
				if (isset($_POST['status'])) {
					$update['status'] = $_POST['status'];
					$duoduo->update('tradelist', $update, 'id=' . $id);
					jump('-2', "订单状态修改成功");					
				}
                jump('-1', '会员名不能为空');
            }
            $user = $duoduo->select('user', 'id,ddusername,level,tjr,trade_uid', 'ddusername="' . $_POST['uname'] . '"');
            if (!$user['id']) {
                jump('-1', '会员不存在');
            }
        } else {
            $user = $duoduo->select('user', 'id,ddusername,level,tjr,trade_uid', 'id=' . $tradelist_data['uid']);
			if (!$user['id']) {
                jump('-1', '会员不存在');
            }
        }
		
		//到这里，会员user是必须存在的，不存在就跳转了
		
		if($webset['taoapi']['auto_fanli']==1 && $_POST['caozuo']!=2){
			$duoduo->trade_uid($user['id'],$tradelist_data['trade_id']);
		}
		
		//有提交订单状态，并且有会员的修改订单状态
		if (isset($_POST['status'])) {
			if($_POST['status']!=5){
				$update['checked'] = 3;
				$update['uid']=$user['id'];
				$update['outer_code']=$user['id'];
				$update['status'] = $_POST['status'];
				$duoduo->update('tradelist', $update, 'id=' . $id);
				jump('-2', "订单状态修改成功");
			}
		}
         //不是结算状态都可以改成已认领
		if($_POST['caozuo']==2){
			$update['checked'] = 0;
			$update['uid'] = 0;
			$update['outer_code'] = '';
			if($_POST['shuoming']){
				$field_arr['addtime']=date('Y-m-d H:i:s');
				$field_arr['see']=0;
				$field_arr['senduser']=0;
				$field_arr['uid']=$user['id'];
				$field_arr['title']="订单认领失败";
				$field_arr['content']=$_POST['shuoming'];
				$duoduo->insert('msg',$field_arr);
			}
			foreach($ids_arr as $id){
				$duoduo->update('tradelist', $update, 'id=' . $id);
			}
			jump('-2', "认领失败");
		}
        if ($tradelist_data['status'] != 5) {
			if($_POST['caozuo']==1){
				$update['checked'] = 3;
				foreach($ids_arr as $id){
					$duoduo->update('tradelist', $update, 'id=' . $id);
				}
				jump('-2', "认领通过");
			}
        }
        //已经返现的只能进行退款操作
        if ($tradelist_data['checked'] == 2 && $do==-1) {
            $re = $duoduo->refund($id, 1);
            jump('-2', $re);
        }
        if ($tradelist_data['checked'] == - 1) {
            jump('-2', "已退款");
        }
        
		if($tradelist_data['commission']==0){
			jump('-1', "佣金不能为0");
		}
		
		foreach($ids_arr as $tradelist_id){ //包含对关联订单的判断
			if($tradelist_data['id']==$tradelist_id){ //如果就是本身订单
				$duoduo->rebate($user, $tradelist_data, 8); //确认淘宝返利
			}
			else{ //是别的订单，只有状态是结算且没返现过的才返现
				$data=$duoduo->select('tradelist','*','id="'.$tradelist_id.'"');
				if($data['status']==5 && ($data['checked']==0 || $data['checked']==1 || $data['checked']==3)){
					$duoduo->rebate($user, $data, 8); //确认淘宝返利
				}
				elseif($data['checked']==0 || $data['checked']==1 || $data['checked']==3){
					$update_data=array('checked'=>3,'uid'=>$user['id'],'outer_code'=>$user['id']);
					$duoduo->update('tradelist', $update_data, 'id=' . $tradelist_id);
				}
			}
		}

        jump('-2', '返现成功');
    } else {
        if ($_POST['uname'] == '') {
            jump('-1', '会员名不能为空');
        }
        $user = $duoduo->select('user', 'id,ddusername,level,tjr', 'ddusername="' . $_POST['uname'] . '"');
        if (!$user['id']) {
            jump('-1', '会员不存在');
        }
        unset($_POST['sub']);
        unset($_POST['uname']);
        unset($_POST['do']);
        unset($_POST['id']);
		$_POST['mini_trade_id']=substr($_POST['trade_id'],0,8).substr($_POST['trade_id'],-4,4);
        $_POST['outer_code'] = $user['id'];
        $_POST['uid'] = $user['id'];
		if($_POST['uid']>0){
			$_POST['checked'] = 3;
		}
        if($_POST['pay_time']!=''){
			$_POST['qrsj'] = TIME;
		}
        
        $id = $duoduo->select('tradelist', 'id', 'trade_id="' . $_POST['trade_id'] . '"');
        if ($id > 0) {
            jump(-1, '订单号不可重复');
        }

        $id = $duoduo->insert('tradelist', $_POST);
        $trade = $duoduo->select('tradelist', '*', 'id="' . $id . '"');
		if($_POST['status']==5){
			$duoduo->rebate($user, $trade, 8); //确认淘宝返利
		}
        jump('-1', '添加成功');
    }
} 
elseif($_POST['sub'] != '' && TAOTYPE==2){
	$id=empty($_POST['id'])?0:(int)$_POST['id'];

	if($id>0){
		$do=(int)$_POST['do'];
		if($do==-1){  //退款订单
	    	$re=$duoduo->refund($id,1);
			jump('-2',$re);
		}
		else{
			if($_POST['uname']==''){
				jump('-1','会员名不能为空');
			}
	    	$user=$duoduo->select('user','id,ddusername,level,tjr','ddusername="'.$_POST['uname'].'"');
			if(!$user['id']){
		    	jump('-1','会员不存在');
			}
			$trade=$duoduo->select('tradelist','*','id="'.$id.'"');
			$duoduo->rebate($user,$trade,8); //确认淘宝返利
			jump('-2','确认成功');
		}
	}
	else{
		if($_POST['uname']==''){
			jump('-1','会员名不能为空');
		}
	    $user=$duoduo->select('user','id,ddusername,level,tjr','ddusername="'.$_POST['uname'].'"');
		if(!$user['id']){
		   	jump('-1','会员不存在');
		}
		unset($_POST['sub']);
		unset($_POST['uname']);
		unset($_POST['do']);
		unset($_POST['id']);
		$_POST['mini_trade_id']=substr($_POST['trade_id'],0,8).substr($_POST['trade_id'],-4,4);
		$_POST['outer_code']=$user['id'];
		$_POST['uid']=$user['id'];
		if($_POST['uid']>0){
			$_POST['checked'] = 3;
		}
        if($_POST['pay_time']!=''){
			$_POST['qrsj'] = TIME;
		}
		
		$id=$duoduo->select('tradelist','id','trade_id="'.$_POST['trade_id'].'"');
		if($id>0){
			jump(-1,'订单号不可重复');
		}

		$id=$duoduo->insert('tradelist',$_POST);
		
		$trade=$duoduo->select('tradelist','*','id="'.$id.'"');
		
		if($_POST['uid']>0){
			$duoduo->rebate($user,$trade,8); //确认淘宝返利
		}
		
		jump('-1','添加成功');
	}
}
else {
    $status_arr = include (DDROOT . '/data/status_arr.php'); //订单状态
    $id = empty($_GET['id']) ? 0 : (int)$_GET['id'];
    if ($id == 0) {
        $row = array();
    } else {
        $row = $duoduo->select(MOD, '*', 'id="' . $id . '"');
    }
}
?>