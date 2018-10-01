<?php  //淘宝商品列表
$css[]=TPLURL."/css/goods.css";
$css[]=TPLURL."/css/list.css";
$js[]="js/md5.js";
$js[]="js/jssdk.js";
include(TPLPATH."/header.tpl.php");
?>
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
                <div class="goodslist_main" id="splistbox">
                    <ul>
                    <div style="height:20px; font-weight:bold; font-size:14px; color:#F30;">当前查询商品</div>
                    <li class="info">
                        <div class="goodslist_main_left" style="filter:gray; -moz-opacity:.5;opacity:0.5">
                        	<div class="goodslist_main_left_img"><a <?=tao_perfect_click($goods['jump'])?> href="<?=TAOTYPE==1?$goods['detail_url']:$goods['jump']?>" target="_blank" pic="<?=base64_encode($goods["pic_url"].'_310x310.jpg')?>"><?=html_img($goods["pic_url"],1,$goods["title"])?></a></div>
                        	<div class="goodslist_main_left_bt title"><a target="_blank" <?=tao_perfect_click($goods['jump'])?> href="<?=TAOTYPE==1?$goods['detail_url']:$goods['jump']?>"><?=$goods["title"]?></a></div>
                            <div class="goodslist_main_left_sell"><p>商品数量<?=$goods["num"]?> 件  </p> </div>
                            <div class="goodslist_main_left_seller"><p>卖家：<?=$goods["nick"]?> <?=wangwang($goods["nick"])?></p>
                            </div>
                        </div>
                        <div class="goodslist_main_right">
                        	<div class="goodslist_main_right_price">
                            <p class="price">淘宝价：<span style="color:#999"><?=$goods["price"]?></span> 元 </p> 
                            <p> <span class="greenfont">暂无返现</span> </p>
                            <p></p>
                            <p id="<?=$goods["num_iid"]?>" class="tbcuxiao" style="clear:both; margin-top:5px; width:150px;"></p>
                        	</div>
                            <div style="clear:both"></div>
                            <div class="goodslist_main_right_tb">
                                <a target="_blank" <?=tao_perfect_click($goods['jump'])?> class="fanlitip" rel="nofollow" href="<?=TAOTYPE==1?$goods['detail_url']:$goods['jump']?>"><div class="goodslist_main_right_buy" style="background-position:-110px -625px; background-color:#999; text-decoration:none">无返利购买</div></a>
                            </div>
                        </div>
                        </li>
                    <li style="height:20px; overflow:hidden; font-weight:bold; font-size:14px; color:#F30;">&nbsp;<?=$guanlian_name?></li>
                    <?php foreach($guanlian_goods as $row) {?>
                        <li class="info" id="<?=$row["iid"]?>">
                        <div class="goodslist_main_left">
                        	<div class="goodslist_main_left_img"><a href="<?=$row["go_view"]?>" target="_blank" pic="<?=base64_encode($row["pic_url"].'_310x310.jpg')?>"><?=html_img($row["pic_url"],1,$row["name"])?></a></div>
                        	<div class="goodslist_main_left_bt title"><a style=" color:#03F" target="_blank" href="<?=$row["go_view"]?>"><?=$row["title"]?></a></div>
                            <?php if(TAOTYPE==2){?>
                            <div class="goodslist_main_left_sell"><p>本期已售出<span><?=$row["volume"]?> </span>件  </p> </div>
                            <div class="goodslist_main_left_seller"><p>卖家：<?=$row["nick"]?></p></div>
                            <?php }?>
                        </div>
                        <div class="goodslist_main_right">
                        	<div class="goodslist_main_right_price">
                            <p class="price">淘宝价：<span><?=$row["price"]?></span> 元 </p> 
                            <?php if(TAOTYPE==2){?>
                            <p class="fxje" title="<?=TBFLTIP?>"> 可返<span class="greenfont"> <?=$row["fxje"]?> </span><?=TBMONEYUNIT?><?=TBMONEY?> </p> 
                            <?php }else{?>
                            <p>&nbsp;<a target="_blank" href="<?=$row["go_view"]?>">详情</a></p>
                            <?php }?>
                            <p></p>
                            <p class="tbcuxiao" style="clear:both; margin-top:5px; width:150px;"></p>
                        	</div>
                            <div style="clear:both"></div>
                            <div class="goodslist_main_right_tb">
                                <a target="_blank" rel="nofollow" href="<?=$row['go_view']?>"><div class="goodslist_main_right_buy">看商品详情</div></a>
                            </div>
                        </div>
                        </li>
                    <?php }?>
                    </ul>
                </div>
                
                
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
<?php include(TPLPATH.'/footer.tpl.php');?>