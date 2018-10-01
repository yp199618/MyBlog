<?php  //淘宝商品列表
$parameter=act_tao_list();
extract($parameter);
$css[]=TPLURL."/css/goods.css";
$css[]=TPLURL."/css/list.css";
$js[]="js/md5.js";
$js[]="js/jssdk.js";
include(TPLPATH."/header.tpl.php");
?>
<?php include(DDROOT.'/comm/jssdk.php');?>
<div class="mainbody">
	<div class="mainbody1000">
        <div class="small_big" id="layerPic">
			<div class="sell_bg"></div>
			<div class="photo"></div>
		 </div>
         
        <div class="goodsleft">
        
        <?php if($cat_list[0]!=''){?>
        <div class="goodslist" style="margin-bottom:10px;">
    <div class="morecat">
      <ul>
      <?php foreach($cat_list as $k=>$v){?>
      <li><a href="<?=u('tao','list',array('cid'=>$v['cid']))?>" <?php if($v['cid']==$cid){?> style="font-weight:bold; color:#F00" <?php }?>)><?=$v['name']?></a></li>
      <?php }?>
      </ul>
      <div style="clear:both"></div>
    </div>
    </div>
    <?php }?>
        	<div class="goodslist">
                <?php if(TAOTYPE==1){include(TPLPATH."/tao/hotcat.tpl.php");}?>
                <?php if(TAOTYPE==2){include(TPLPATH."/tao/hotword.tpl.php");}?>
                <?php if(TAOTYPE==1){$listtpl=$list+2;}else{$listtpl=$list;} include(TPLPATH."/tao/list".$listtpl.".tpl.php");?>
                <?php if(empty($goods)){?>
                     <div style="margin-left:10px; margin-bottom:30px; font-size:14px">暂无查询条件商品数据！</div>
                     <?php }?> 
                <div class="megas512" ><?=pageft($TotalResults,$pagesize,$show_page_url,WJT)?></div>
            </div>
            
        </div> 
        <div class="goodsright">
            <?php if(TAOTYPE==1){include(TPLPATH."/tao/right_goods.tpl.php");}?>
        	<?php if(TAOTYPE==2){include(TPLPATH."/tao/right_category.tpl.php");}?>
              <?=AD(3)?>      
        </div>
        <div style="clear:both"></div>
        <?=AD(108)?>
	</div>
</div>	

<script type="text/javascript" src="js/jquery.lazyload.js"></script>
<script language="javascript">
function ddShowShopInfo(shopsInfo){
	for(var i in shopsInfo){
		var goodsdom=$('#splistbox .info');
		if(shopsInfo[i]['level']>0){
			$(goodsdom).find('img[zhanggui='+shopsInfo[i]['seller_nick']+']').attr('src','images/level_'+shopsInfo[i]['level']+'.gif');
			var plurl=$(goodsdom).find('a[zhangguiid='+shopsInfo[i]['seller_nick']+']').attr('url');
			$(goodsdom).find('a[zhangguiid='+shopsInfo[i]['seller_nick']+']').attr('url','userNumId='+shopsInfo[i]['user_id']+plurl);
		}
		else{
			$(goodsdom).find('img[zhanggui='+shopsInfo[i]['seller_nick']+']').remove();
		}
	}
}
$(function(){
	$("div.pic a img").lazyload({
        placeholder : "<?=TPLURL?>/images/grey.gif",
        effect      : "fadeIn",
	    threshold : 200
    });
	
	<?php if(TAOTYPE==2){
	$jssdk_shops_convert['method']='taobao.taobaoke.widget.shops.convert';
	$jssdk_shops_convert['count']=count($nick_arr);
	$jssdk_shops_convert['from']='list';
	$jssdk_shops_convert['fields']='seller_nick,user_id,seller_credit,shop_type';
	$nicks_array=str2arr($nick_arr,10);
	foreach($nicks_array as $strnicks){
		$jssdk_shops_convert['seller_nicks']=$strnicks;
		php2js_array($jssdk_shops_convert,'parame');
		echo "taobaoTaobaokeWidgetShopsConvert(parame);\r\n";
	}}?>
})
</script>
<?php include(TPLPATH."/footer.tpl.php");?>