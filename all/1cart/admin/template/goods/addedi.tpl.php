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

if(isset($_GET['nick'])){
	$nick=$_GET['nick'];
	include (DDROOT . '/comm/Taoapi.php');
	include (DDROOT . '/comm/ddTaoapi.class.php');
	$ddTaoapi = new ddTaoapi();
	$shop=$ddTaoapi->taobao_shop_get($nick);
	print_r($shop['sid']);
	exit;
}


include(ADMINTPL.'/header.tpl.php');
$type_arr=$duoduo->select_all('type','id,title','tag="'.MOD.'"');
foreach($type_arr as $info){
	$type[$info['id']]=$info['title'];
}
include(DDROOT.'/comm/jssdk.php');
$credit=include(DDROOT.'/data/tao_level.php');
$credit[21]='天猫商城';
?>
<?php if(TAOTYPE==1){?>
<script id="jssdk" src=""></script>
<?php }else{?>
<script src="http://l.tbcdn.cn/apps/top/x/sdk.js?appkey=<?=$webset['taoapi']['jssdk_key']?>"></script>
<?php }?>
<?php include(DDROOT.'/comm/jssdk.php');?>
<script src="../js/jssdk.js"></script>
<script src="../js/md5.js"></script>
<script>
SITEURL="<?=SITEURL?>/";
CURURL="<?=CURURL?>/";
regTaobaoUrl = /(.*\.?taobao.com(\/|$))|(.*\.?tmall.com(\/|$))/i; 
function getTaoItem(url){
    if(url==''){
		alert('网址不能为空！');
		return false;
	}
	if (!url.match(regTaobaoUrl)){
		alert('这不是一个淘宝网址！');
		return false;
	}
	
	<?php
	$tao_id_arr=include (DDROOT.'/data/tao_ids.php');
	$ids=implode('|',$tao_id_arr);
	?>
	
	var iid=0;
	var a=url.match(/[&|?](<?=$ids?>)=(\d+)/);
	if(typeof a[2]!='undefined'){
		iid=a[2];
	}
	else{
		a=url.match('/\/(\d+)\.htm/');
		if(typeof a[1]!='undefined'){
			iid=a[1];
		}
	}
	
	if(iid==0){
		alert('商品网址无效，请人工添加数据');
		return false;
	}

	var parame=new Array();
	parame['num_iids']=iid;
	parame['onlyComm']=1;
	taobaoTaobaokeWidgetItemsConvert(parame);
}
function ddShowFxje(taobaokeItem){
	if(taobaokeItem.ddFxje==0){
		alert('此商品无返利或者不存在！');
		return false;
	}
	$('#title').val(taobaokeItem.title);
	$('#pic_url').val(taobaokeItem.pic_url);
	$('#iid').val(taobaokeItem.num_iid);
	$('#price').val(taobaokeItem.price);
	$('#commission').val(taobaokeItem.commission);
	$('#nick').val(taobaokeItem.nick);
	shop(taobaokeItem.nick)
	$('#credit').val(taobaokeItem.seller_credit_score);
	$('#volume').val(taobaokeItem.volume);
	if(parseFloat(taobaokeItem.promotion_price)<parseFloat(taobaokeItem.price)){
		$('#promotion_price').val(taobaokeItem.promotion_price);
	}
	else{
		$('#promotion_price').val(0);
	}
	if(taobaokeItem.promotion==1){
		$('#promotion_name').val('特价促销');
	}
	else{
		$('#promotion_name').val('');
	}
}

function shop(nick){
	$.get('<?=u(MOD,ACT)?>',{nick:nick},function(data){
		$('#shop_id').val(data);
	});
	var apiParame={method:'taobao.taobaoke.widget.shops.convert', fields:'shop_id,seller_nick,user_id,shop_title,click_url,commission_rate,seller_credit,shop_type,total_auction,auction_count',seller_nicks :nick,timestamp:JSSDK_TIME,sign:JSSDK_SIGN};
    TOP.api('rest','get',apiParame,function(resp){
    	if(isEmpty(resp)==false){
			if(resp.taobaoke_shops.taobaoke_shop[0].shop_type=='B'){
				$('#credit').val(21);
			}
			$('#user_id').val(resp.taobaoke_shops.taobaoke_shop[0].user_id);
        }
	});
}
</script>
<form action="index.php?mod=<?=MOD?>&act=<?=ACT?>" method="post" name="form1">
<div class="explain-col"> 获取淘宝商品信息，只需填写淘宝网址，点击“获取商品详情”，系统便可自动采集商品信息
  </div>
<br />
<table id="addeditable" border=1 cellpadding=0 cellspacing=0 bordercolor="#dddddd">
  <tr>
    <td width="115px" align="right">淘宝网址：</td>
    <td>&nbsp;<input type="text" id="url" value="<?=$row['iid']?'http://item.taobao.com/item.htm?id='.$row['iid']:''?>" style="width:300px" /> <input onClick="getTaoItem($('#url').val())" class="sub" type="button" value="获取商品详情" /></td>
  </tr>
  <tr>
    <td align="right">分类：</td>
    <td>&nbsp;<?=select($type,$row['cid']?$row['cid']:$_GET['cid'],'cid')?></td>
  </tr>
  <tr>
    <td align="right">精品推荐：</td>
    <td>&nbsp;<?=html_radio(array(1=>'推荐',0=>'不推荐'),$row['tuijian'],'tuijian')?>&nbsp;&nbsp;选择推荐之后显示在列表页和详情页面的“精品推荐”里，显示排序最高的5个</td>
  </tr>
  <tr>
    <td width="115px" align="right">标题：</td>
    <td>&nbsp;<input name="title" type="text" id="title" value="<?=$row['title']?>" style="width:300px" /></td>
  </tr>
  <tr>
    <td align="right">图片：</td>
    <td>&nbsp;<input name="pic_url" type="text" id="pic_url" value="<?=$row['pic_url']?>" style="width:300px" /> <input class="sub" type="button" value="上传图片" onclick="javascript:openpic('<?=u('fun','upload',array('uploadtext'=>'pic_url','sid'=>session_id()))?>','upload','450','350')" /> 可直接添加网络地址</td>
  </tr>
  <tr>
    <td align="right">商品id：</td>
    <td>&nbsp;<input name="iid" type="text" id="iid" value="<?=$row['iid']?>" /></td>
  </tr>
  <tr>
    <td align="right">价格：</td>
    <td>&nbsp;<input name="price" type="text" id="price" value="<?=$row['price']?>" /></td>
  </tr>
  <tr>
    <td align="right">佣金：</td>
    <td>&nbsp;<input name="commission" type="text" id="commission" value="<?=$row['commission']?>" /></td>
  </tr>
  <tr>
    <td align="right">掌柜：</td>
    <td>&nbsp;<input name="nick" type="text" id="nick" value="<?=$row['nick']?>" /></td>
  </tr>
  <tr>
    <td align="right">卖家信用：</td>
    <td>&nbsp;<?=select($credit,$row['credit'],'credit')?></td>
  </tr>
  <tr>
    <td align="right">近期销量：</td>
    <td>&nbsp;<input name="volume" type="text" id="volume" value="<?=$row['volume']?>" /></td>
  </tr>
  <tr>
    <td align="right">促销价格：</td>
    <td>&nbsp;<input name="promotion_price" type="text" id="promotion_price" value="<?=$row['promotion_price']?>" />&nbsp;没填的话显示上面的价格</td>
  </tr>
  <tr>
    <td align="right">促销类型：</td>
    <td>&nbsp;<input name="promotion_name" type="text" id="promotion_name" value="<?=$row['promotion_name']?>" />&nbsp;4个字描述；例：今日特价</td>
  </tr>
  <tr>
    <td align="right">是否包邮：</td>
    <td>&nbsp;<?=html_radio($shifou_arr,$row['baoyou'],'baoyou')?></td>
  </tr>
  <tr>
    <td align="right">淘宝会员id：</td>
    <td>&nbsp;<input name="user_id" type="text" id="user_id" value="<?=$row['user_id']?$row['user_id']:0?>" /></td>
  </tr>
  <tr>
    <td align="right">淘宝店铺id：</td>
    <td>&nbsp;<input name="shop_id" type="text" id="shop_id" value="<?=$row['shop_id']?$row['shop_id']:0?>" /></td>
  </tr>
  

  <tr>
    <td align="right">排序：</td>
    <td>&nbsp;<input name="sort" type="text" id="sort" value="<?=$row['sort']?$row['sort']:0?>"  /> 数字越大越靠前</td>
  </tr>
  <?php if($_POST['sub']!=''){?>
  <tr>
    <td align="right">添加时间：</td>
    <td>&nbsp;<input name="addtime" type="text" id="addtime" value="<?=date('Y-m-d H:i:s',$row['addtime'])?>"  /></td>
  </tr>
  <?php }?>
  <tr>
    <td align="right">&nbsp;</td>
    <td>&nbsp;<input type="hidden" name="id" value="<?=$row['id']?>" /><input type="submit" class="sub" name="sub" value=" 保 存 " /></td>
  </tr>
</table>
</form>
<?php include(ADMINTPL.'/footer.tpl.php');?>