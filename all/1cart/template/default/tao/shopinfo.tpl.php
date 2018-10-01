<div class="shopmessage">
                <div class="shopname">
                	<h3><A class="dd_shop_jump" href="" <?=$login_click?> target=_blank><?=$shop['title']?></A></h3>
                </div>
                <div class="shoplogo">
                    <div class="shoplogo-img"><?=html_img($shop['logo'],0,$shop['title'],'',80,80,$shop['onerror'])?></div>
                    <div class="shoplogo-font"><p>平均:</p><h3 id="shopFxbl"></h3></div>
                </div>
                <div class="shoptxt">
                <p>掌柜名称：<a class="dd_shop_jump" href="" <?=$login_click?> target="_blank" ><?=$shop['nick']?></a></p>
                <p>店铺信誉：<span class="shoptxt-img"><img src="images/level_<?=$shop['level']?>.gif" ></span></p>
                <p id="goodsnum">宝贝数量：</p>
                <p>创店时间：<?=date('Y-m-d',strtotime($shop['created']))?></p>
                <b>店铺动态评分</b>
                <div class="shoptxt-dt">	
                    <DIV class=title>宝贝与描述相符：</DIV>
                    <DIV class=xx3>
                    <DIV style="WIDTH: 100px; BACKGROUND: url(<?=TPLURL?>/images/5bx.gif) no-repeat 0px 0px; FLOAT: left; HEIGHT: 19px" title="<?=$shop['item_score']?>分">
                    <DIV style="WIDTH: 100px; BACKGROUND: url(<?=TPLURL?>/images/5hx.gif) 0px 0px; HEIGHT: 19px; width:<?=$shop['item_score']*20?>px">
                    </DIV>
                    </DIV>
                    </DIV>
                </div>
                <div class="shoptxt-dt">
                	<DIV class=title>卖家的服务态度：</DIV>
                    <DIV class=xx3>
                    <DIV style="WIDTH: 100px; BACKGROUND: url(<?=TPLURL?>/images/5bx.gif) no-repeat 0px 0px; FLOAT: left; HEIGHT: 19px" title="<?=$shop['service_score']?>分">
                    <DIV style="WIDTH: 100px; BACKGROUND: url(<?=TPLURL?>/images/5hx.gif) 0px 0px; HEIGHT: 19px; width:<?=$shop['service_score']*20?>px">
                    </DIV>
                    </DIV>
                    </DIV>
                </div>
                <div class="shoptxt-dt">
                	<DIV class=title>卖家发货的速度：</DIV>
                    <DIV class=xx3>
                    <DIV style="WIDTH: 100px; BACKGROUND: url(<?=TPLURL?>/images/5bx.gif) no-repeat 0px 0px; FLOAT: left; HEIGHT: 19px" title="<?=$shop['delivery_score']?>分">
                    <DIV style="WIDTH: 100px; BACKGROUND: url(<?=TPLURL?>/images/5hx.gif) 0px 0px; HEIGHT: 19px; width:<?=$shop['delivery_score']*20?>px">
                    </DIV>
                    </DIV>
                    </DIV>
                </div>
              
                <div class="shopbutton">
                <A class="dd_shop_jump" href="" <?=$login_click?> target=_blank>
					<DIV class=shop_button1 onMouseOver="this.className='shop_button1_h';" onmouseout="this.className='shop_button1';"></DIV>
                </A>
                </div>
              </div>
            </div>
<script>
function showShopInfo(){
	<?php
	$parame['seller_nicks']=$nick;
	php2js_array($jssdk_shops_convert,'parame');
	echo "taobaoTaobaokeWidgetShopsConvert(parame);\r\n";
	?>
}

<?php if(MOD=='tao' && ACT=='shop'){?>
showShopInfo();
<?php }?>

commentUrl='';
function ddShowShopInfo(shopsInfo){
	if(shopsInfo['level']>0){
		$('.shopmessage #shopFxbl').html(shopsInfo['fxbl']+'%');
		$('.shopmessage .shoptxt-img img').attr('src','images/level_'+shopsInfo['level']+'.gif');
		$('.shopmessage #goodsnum').html('宝贝数量：'+shopsInfo['auction_count']);
		<?php if(TAOTYPE==1){?>
		$('.dd_shop_jump').attr('href','http://shop'+shopsInfo['sid']+'.taobao.com');
		<?php }else{?>
		$('.dd_shop_jump').attr('href',shopsInfo['jump']);
		<?php }?>
		
		<?php if(MOD=='tao' && ACT=='shop'){?>
		
		var str;
		var goodsUrl='<?=SITEURL?>/index.php?mod=ajax&act=shop_items_get&uid='+shopsInfo['user_id']+'&nick='+encodeURIComponent(shopsInfo['seller_nick'])+'&taoke='+shopsInfo['taoke']+'&level='+shopsInfo['level'];

		$.ajax({
	    	url: goodsUrl,
			dataType:'jsonp',
			jsonp:"callback",
			cache:false,
			success: function(data){
				if(isEmpty(data)){
					tpl='暂无商品';
				}
				else{
					tpl=getTplObj(html_tpl_<?=$list?>,data);
				}
				$('#splistbox ul').append(tpl);
			}
		});
		<?php }?>
		
		<?php if(MOD=='tao' && ACT=='view'){?>
		commentUrl='<?=$comment_url?>'+shopsInfo['user_id'];
		<?php }?>
	}
}

function html_tpl_1() {/*<li class="info">
                        <div class="goodslist_main_left">
                        	<div class="goodslist_main_left_img"><a class="taopic" href="{$gourl}" target="_blank" pic="{$pic_url_base64_encode}"><img src="{$pic_url}" /></a></div>
                        	<div class="goodslist_main_left_bt title"><a target="_blank" href="{$gourl}">{$title}</a></div>
                            <div class="goodslist_main_left_sell"><p>本期已售出<span>{$commission_num} </span>件 <img alt="等级" src="images/level_{$shoplevel}.gif" align="absmiddle" /> </p> </div>
                            <div class="goodslist_main_left_seller"><p>卖家：<A href="{$go_shop}" target=_blank title="逛逛{$nick}的店铺">{$nick}</a> {$wangwang_nick}</p>
                            </div>
                        </div>
                        <div class="goodslist_main_right">
                        	<div class="goodslist_main_right_price">
                            <p class="price">淘宝价：<span>{$price}</span> 元 </p> 
                            <p class="fxje" title="<?=TBFLTIP?>"> 可返<span class="greenfont">{$fxje}</span><?=TBMONEYUNIT?><?=TBMONEY?> </p> 
                            <p>&nbsp;<a target="_blank" href="{$go_view}">详情</a></p>
                            <p id="{$num_iid}" class="tbcuxiao" style="clear:both; margin-top:5px; width:150px;"></p>
                        	</div>
                            <div style="clear:both"></div>
                            <div class="goodslist_main_right_tb">
                                <a target="_blank" href="{$tao_list_url}"><div class="goodslist_main_right_bj"></div></a>
                                <a target="_blank" rel="nofollow" href="{$jump}"><div class="goodslist_main_right_buy">去淘宝购买</div></a>
                            </div>
                        </div>
                        </li>*/;}

function html_tpl_2() {/*<li class="info" style=" height:390px">
                            <div class="goodslist_main_left_img_2"><a href="{$gourl}" target="_blank"><img src="{$pic_url}" style=" width:160px; height:160px" /></a></div>
                        	<div class="goodslist_main_left_bt_2 title"><a target="_blank">{$title}</a></div>
                            <div class="goodslist_main_left_xy_2"><p>卖家信用：<img alt="等级" src="images/level_{$shoplevel}.gif" align="absmiddle" /></p> </div>
                            <div class="goodslist_main_left_seller_2"><p>卖家：<A href="{$go_shop}" target=_blank title="逛逛{$nick}的店铺">{$nick}</a> {$wangwang_nick}</p>
                            </div>
                        	<p class="price">淘宝价：<span>{$price}</span> 元 </p> 
                            <p class="tbcuxiao">淘宝热卖商品</p>
                            <p class="fxje" title="<?=TBFLTIP?>"> 可返：<span class="greenfont">{$fxje}</span><?=TBMONEYUNIT?><?=TBMONEY?> </p>
                            <div class="goodslist_main_right_tb_2">
                                  <a rel="nofollow" href="{$jump}" target="_blank" ><div class="goodslist_main_right_buy">去淘宝购买</div></a>
                            </div>
                        </li>*/;}
</script>