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
* @name 淘宝商品详情
* @copyright duoduo123.com
* @example 示例tao_view();
* @param $field 字段
* @param $q 关键字
* @param $iid 商品ID
* @return $parameter 结果集合
*/
function act_tao_view(){
	global $duoduo,$ddTaoapi;
	$webset = $duoduo->webset;
	$dduser = $duoduo->dduser;
	$tao_id_arr = include (DDROOT.'/data/tao_ids.php');
	$shield_cid = include (DDROOT.'/data/shield_cid.php');
	$virtual_cid=include (DDROOT.'/data/virtual_cid.php');
	if(empty($iid)){
		$iid=isset($_GET['iid'])?(float)$_GET['iid']:'';
	}
	if(empty($q)){
		$q=isset($_GET['q'])?$_GET['q']:'';
	}
	$promotion_name=$_GET['promotion_name']?$_GET['promotion_name']:'促销打折';
	$price_name='一&nbsp;口&nbsp;价';
	
	$is_url=0;
	$is_mall=0;
	$is_ju=0;
	$url='';
	if(reg_taobao_url($q)==1){
		$is_url=1;
		$url=$q;
		$iid=(float)get_tao_id($q,$tao_id_arr);
		if($iid==0){
			error_html('请使用标准淘宝商品网址搜索！');
		}
		
		$ju_url='http://a.m.taobao.com/i'.$iid.'.htm';
		$ju_html=file_get_contents($ju_url);
		if($ju_html==''){  //个别主机不能采集淘宝手机页面
			$ju_url='http://ju.taobao.com/tg/home.htm?item_id='.$iid;
			$ju_html=dd_get($ju_url);
			if($ju_html!='' && strpos($ju_html,'<input type="hidden" name="item_id" value="'.$iid.'"')!==false){
				$is_juhuasuan=2;  //一般网页
			}
		}
		elseif(strpos($ju_html,'<a name="'.$iid.'"></a>')!==false){
			$is_juhuasuan=1;  //手机网页
		}

		if($is_juhuasuan>0){
			$q=$url='http://ju.taobao.com/tg/home.htm?item_id='.$iid;
			if($is_juhuasuan==1){
				preg_match('/style="color:#ffffff;">￥(.*)<\/span>/',$ju_html,$a);
			}
			else{
				preg_match('/<strong class="J_juPrices"><b>&yen;<\/b>(.*)<\/strong>/',$ju_html,$a);
			}
			$ju_price=(float)$a[1];
			$goods_type='ju';
			$jssdk_items_convert['goods_type']=$goods_type;  //聚划算
			$jssdk_items_convert['ju_price']=$ju_price;
			$price_name='<img src="images/ju-icon.png" alt="聚划算" />';
		}
		elseif(strpos($q,'tmall.com')!==false){
			$goods_type='tmall';
			$jssdk_items_convert['goods_type']=$goods_type;  //天猫
			$price_name='<b style="color:#a91029">天猫正品</b>';
		}
		else{
			$goods_type='market';
			$jssdk_items_convert['goods_type']=$goods_type;  //集市
		}
	}
	elseif($iid==0){
		if($webset['taoapi']['s8']==1){
			$url=$ddTaoapi->taobao_taobaoke_listurl_get($q,$dduser['id']);
			$url=$goods['jump']="index.php?mod=jump&act=s8&url=".urlencode(base64_encode($url)).'&name='.urlencode($q);
			jump($url);
		}
		else{
			error_html('直接搜索淘宝商品网址即可查询返利',5);
		}
	}
	$data['iid']=$iid;
	$data['outer_code']=$dduser['id'];
	$data['all_get']=1; //商品没有返利也获取商品内容
	$data['goods_type']=$goods_type;
	$data['ju_price']=$ju_price;
	
	if(TAOTYPE==1){
		$data['fields']='iid,detail_url,num_iid,title,nick,type,cid,pic_url,seller_cids,num,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,item_img';
		if(WEBTYPE==1){
			$data['fields'].=',desc';
		}
		$goods=$ddTaoapi->taobao_item_get($data);
		if(BROWSER==1){  //浏览器访问获取返利授权，节约api
			$allow_fanli=$ddTaoapi->taobao_taobaoke_rebate_authorize_get($iid);
			if($allow_fanli==0){
				$guanlian_name='推荐商品';
				$guanlian_goods=$ddTaoapi->taobao_itemrecommend_items_get($iid);
			}
		}
	}
	else{
		$data['fields']='iid,detail_url,num_iid,title,nick,type,cid,pic_url,num,list_time,delist_time,stuff_status,location,price,post_fee,express_fee,ems_fee,has_discount,freight_payer,seller_credit_score,shop_click_url,click_url,volume,stuff_status,has_invoice,auction_point';
		if(WEBTYPE==1){
			$data['fields'].=',desc';
		}
		$goods=$ddTaoapi->items_detail_get($data);
		if(BROWSER==1){  //浏览器访问获取返利授权，节约api
			$allow_fanli=$ddTaoapi->taobao_taobaoke_rebate_authorize_get($iid);
			if($allow_fanli==0){
				$Tapparams['keyword']=$goods['title']; 
				$Tapparams['page_no']=1;
				$Tapparams['page_size']=20;
				//$Tapparams['sort']=$webset['taoapi']['sort']; 
				$Tapparams['outer_code']=$dduser['id'];
				$guanlian_goods=$ddTaoapi->taobao_taobaoke_items_get($Tapparams);
				$guanlian_name='同名商品';
		
				foreach($guanlian_goods as $key=>$vo){
					if($vo['num_iid']==$goods['num_iid']){
						unset($guanlian_goods[$key]);
					}
				}
				unset($Tapparams);
		
				if($guanlian_goods==102||count($guanlian_goods)<5){
					if(!is_array($guanlian_goods)){
						$guanlian_goods=array();
					}
					$Tapparams['outer_code']=$dduser['id'];
					$Tapparams['relate_type']=1;
					$Tapparams['num_iid']=$iid;
					$Tapparams['max_count']=GUANLIAN_NUM*2;
					$a=$ddTaoapi->taobao_taobaoke_items_relate_get($Tapparams);
					$guanlian_goods=array_merge($guanlian_goods,$a);
					$guanlian_name='推荐商品';
				}
			}
		}
		else{
			$allow_fanli=1;
		}
	}
	
	if($goods['title']=='' || $goods['num']==0 || ($webset['taoapi']['shield']==1 && in_array($goods['cid'],$shield_cid))){
		error_html('商品不存在或已下架或者是违禁商品。<a target="_blank" href="http://item.taobao.com/item.htm?id='.$iid.'">去淘宝确认</a>',-1,1);
	}
	
	if($allow_fanli==1){
		$jssdk_items_convert['method']='taobao.taobaoke.widget.items.convert';
		$jssdk_items_convert['outer_code']=(int)$dduser['id'];
		$jssdk_items_convert['user_level']=(int)$dduser['level'];
		$jssdk_items_convert['num_iids']=$iid;
		$jssdk_items_convert['allow_fanli']=$allow_fanli;
		$jssdk_items_convert['cid']=$goods['cid'];
		$jssdk_items_convert['tmall_fxje']=(float)$goods['tmall_fxje'];
		$jssdk_items_convert['ju_fxje']=(float)$goods['ju_fxje'];
		$jssdk_items_convert['goods_type']=$goods_type;
	
		$nick=$goods['nick'];
	
		include(DDROOT.'/mod/tao/shopinfo.act.php'); //店铺信息
	
		if(WEBTYPE==1 && TAOTYPE==2){
			$Tapparams['cid']=$goods['cid']; //当前cid热卖商品
			$Tapparams['page_size']=6;
			$Tapparams['start_credit']='1crown';
			$Tapparams['end_credit']='5goldencrown';
			$Tapparams['start_price']='20';
			$Tapparams['end_price']='5000';
			$Tapparams['sort']='commissionNum_desc';
			$Tapparams['outer_code'] = $dduser['id'];
			$goods2=$ddTaoapi->taobao_taobaoke_items_get($Tapparams);
		}
	
		$comment_url="http://rate.taobao.com/detail_rate.htm?&auctionNumId=".$iid."&showContent=2&currentPage=1&ismore=1&siteID=7&userNumId=";
	
		include(DDROOT.'/plugin/tao_coupon.php');
	
		$parameter['tao_id_arr']=$tao_id_arr;
		$parameter['shield_cid']=$shield_cid;
		$parameter['virtual_cid']=$virtual_cid;
		$parameter['iid']=$iid;
		$parameter['q']=$q;
		$parameter['promotion_name']=$promotion_name;
		$parameter['price_name']=$price_name;
		$parameter['tao_coupon_str']=$tao_coupon_str;
		$parameter['url']=$url;
		$parameter['data']=$data;
		$parameter['goods']=$goods;
		$parameter['goods2']=$goods2;
		$parameter['comment_url']=$comment_url;
		$parameter['nick']=$nick;
		$parameter['allow_fanli']=$allow_fanli;
		$parameter['jssdk_items_convert']=$jssdk_items_convert;
	}
	else{
		$parameter['jssdk_items_convert']=$jssdk_items_convert;
		$parameter['goods']=$goods;
		$parameter['guanlian_goods']=$guanlian_goods;
		$parameter['jssdk_items_convert']=$jssdk_items_convert;
		$parameter['allow_fanli']=$allow_fanli;
		$parameter['guanlian_name']=$guanlian_name;
	}
	
	unset($duoduo);
	return $parameter;
}
?>