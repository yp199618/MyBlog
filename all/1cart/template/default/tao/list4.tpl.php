<div class="goodslist_main_2" id="splistbox">
                    <ul>
                    <?php foreach($goods as $row) {?>
                        <li class="info" style=" height:350px">
                            <div class="goodslist_main_left_img_2"><a <?=webtype('rel="nofollow" class="fanlitip"')?> href="<?=$row["go_view"]?>" target="_blank"><?=html_img($row["pic_url"],2,$row["title"],'',160,160)?></a></div>
                        	<div class="goodslist_main_left_bt_2 title"><a target="_blank" <?=webtype('rel="nofollow" class="fanlitip"')?> href="<?=$row["go_view"]?>"><?php echo $row["title"] ?></a></div>
                            <div class="goodslist_main_left_xy_2"><p>卖家信用：<img zhanggui='<?=$row["nick"]?>' alt="等级" src="images/level_<?=$row['credit']?>.gif" align="absmiddle" /></p> </div>
                            <div class="goodslist_main_left_seller_2"><p>卖家：<A href="<?=$row["go_shop"]?>" target=_blank title="逛逛<?=$row["nick"]?>的店铺"><?=$row["nick"]?></a> <?=wangwang($row["nick"],2)?></p>
                            </div>
                            <?php if($row['promotion_price']>0){?>
                            <p class="price"><span class="tbcuxiao"><i style="font-weight:normal; font-size:12px"><?=$row["promotion_name"]?></i></span>：<span><?=$row["promotion_price"]?></span> 元 </p> 
                            <?php }else{?>
                        	<p class="price">淘宝价：<span><?=$row["price"]?></span> 元 </p> 
                            <?php }?>
                            <p class="fxje" title="<?=TBFLTIP?>"> 可返：<span class="greenfont"><?=$row["fxje"]?></span><?=TBMONEYUNIT?><?=TBMONEY?> </p>
                            <div class="goodslist_main_right_tb_2">
                                  <a rel="nofollow" class="fanlitip" href="<?=$row['go_view']?>" target="_blank" ><div class="goodslist_main_right_buy">去淘宝购买</div></a><?php if($webset['taoapi']['goods_comment']==1){?>&nbsp;&nbsp; (<a zhangguiid='<?=$row["nick"]?>' url="&auctionNumId=<?=$row["iid"]?>&userNumId=<?=$row["user_id"]?>" goto="<?=$goods['go_taobao']?>" style="color:#06F; text-decoration:underline; cursor:pointer" class="seecomment">查看评价</a>) <?php }?>
                            </div>
                        </li>  
                     <?php }?> 
                                 
                    </ul>
                </div>	
<?php include(TPLPATH.'/tao/listjs.tpl.php')?>