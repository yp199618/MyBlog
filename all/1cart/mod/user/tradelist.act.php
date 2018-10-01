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

if (!defined('INDEX')) {
	exit ('Access Denied');
}

function act_user_tradelist($pagesize = 10) {
	global $duoduo;
	$webset = $duoduo->webset;
	$dduser = $duoduo->dduser;
	$do = empty ($_GET['do']) ? 'taobao' : $_GET['do'];
	$page = !($_GET['page']) ? '1' : intval($_GET['page']);
	$pagesize = 10;
	$frmnum = ($page -1) * $pagesize;
	$cat_arr = $webset['baobei']['cat'];
	$status_arr = include (DDROOT . '/data/status_arr.php'); //订单状态
	if ($do == 'taobao') {
		$total = $duoduo->count('tradelist', 'uid="' . $dduser['id'] . '" and (checked=2 or checked=3)');
		$dingdan = $duoduo->left_join('tradelist as a', 'baobei AS b ON a.id = b.trade_id', 'a.*, b.id as baobei_id', "a.uid=" . $dduser['id'] . " and (checked=2 or checked=3) order by a.id desc limit $frmnum,$pagesize");
	}
	elseif ($do == 'lost') {
		if (isset ($_GET['q'])) {
			$q = $_GET['q'];
			$where = ' and trade_id = "' . $q . '"';
			$tradelist_data = $duoduo->select('tradelist', 'id,guanlian', 'uid=0' . $where);
			if ($tradelist_data['guanlian']) {
				$guanlian = $ziguanlian = explode(",", $tradelist_data['guanlian']);
				array_unshift($guanlian, $tradelist_data['id']);
				$total = $duoduo->count('tradelist', 'uid=0 and id in(' . implode(",", $guanlian) . ')');
				$where = ' and id in(' . implode(",", $guanlian) . ')';
			} else {
				$where = ' and id =' . $tradelist_data['id'];
				$ziguanlian=array();
			}
			$total = $duoduo->count('tradelist', 'uid=0 ' . $where);
			$dingdan = $duoduo->select_all('tradelist', '*', 'uid=0 ' . $where . ' limit ' . $frmnum . ',' . $pagesize);
			if (empty ($dingdan)) {
				$msg = "订单号为" . $q . "的订单不存在或者已经被认领";
			}
		}
	}
	elseif ($do == 'paipai') {
		$total = $duoduo->count('paipai_order', 'uid="' . $dduser['id'] . '"');
		$dingdan = $duoduo->select_all('paipai_order', '*', 'uid="' . $dduser['id'] . '" order by  id desc limit ' . $frmnum . ',' . $pagesize);
	}
	elseif ($do == 'paipailost') {
		if (isset ($_GET['q'])) {
			$q = $_GET['q'];
			$where = ' and dealId = "' . $q . '"';
			$total = $duoduo->count('paipai_order', 'uid=0' . $where);
			$dingdan = $duoduo->select_all('paipai_order', '*', 'uid=0' . $where . ' limit ' . $frmnum . ',' . $pagesize);
		}
	}
	elseif ($do == 'mall') {
		$total = $duoduo->count('mall_order', 'uid="' . $dduser['id'] . '"');
		$dingdan = $duoduo->select_all('mall_order', '*', 'uid="' . $dduser['id'] . '" order by  order_time desc limit ' . $frmnum . ',' . $pagesize);
	}
	elseif ($do == 'malllost') {
		if (isset ($_GET['q'])) {
			$q = $_GET['q'];
			$where = ' and order_code = "' . $q . '"';
			$total = $duoduo->count('mall_order', 'uid=0' . $where);
			$dingdan = $duoduo->select_all('mall_order', '*', 'uid=0' . $where . ' limit ' . $frmnum . ',' . $pagesize);
		}
	}
	elseif ($do == 'checked') {
		$total = $duoduo->count('tradelist', 'uid="' . $dduser['id'] . '" and checked=1');
		$dingdan = $duoduo->select_all('tradelist', '*', 'uid="' . $dduser['id'] . '" and checked=1 order by id desc limit ' . $frmnum . ',' . $pagesize);
	}
	unset ($duoduo);
	$parameter['do'] = $do;
	$parameter['cat_arr'] = $cat_arr;
	$parameter['status_arr'] = $status_arr;
	$parameter['total'] = $total;
	$parameter['pagesize'] = $pagesize;
	$parameter['dingdan'] = $dingdan;
	$parameter['q'] = $q;
	$parameter['ziguanlian'] = $ziguanlian;
	return $parameter;
}
?>