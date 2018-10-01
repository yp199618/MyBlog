<?php
$parameter=act_tao_index();
extract($parameter);
$css[]=TPLURL."/css/tao_index.css";
include(TPLPATH.'/header.tpl.php');
?>
<script src="<?=TPLURL?>/js/jquery.KinSlideshow-1.2.1.min.js" type="text/javascript"></script>
<script>
$(function(){
	<?php if(!empty($slides)){?>
	$("#KinSlideshow").KinSlideshow({
		isHasTitleFont:false,
		isHasTitleBar:false,
		btn:{btn_fontHoverColor:"#FFFFFF"}
	});
	<?php }?>
	$('.url-how-to span').hover(function(){
	    $(this).next('.hover-tips').show();
	},function(){
		$(this).next('.hover-tips').hide();
	});
	
    $('#content form').jumpBox({  
		LightBox:'show',
		jsCode:'$content.html($("#searchtip").html());',
		reg:'reg_url($("#inner-offer-q").val())',
		height:250,
		width:555,
		bind:'submit',
		a:1,
		background:'url(images/xiexian.gif) #FFFFFF'
    });
})
</script>
<div style="width:1000px; background:#FFF; border:#D0210C 1px solid; margin:auto; margin-top:10px; padding-bottom:10px">
<div id="main" style="width:950px; margin:auto;">
<div id="content" class="span-24" style="height:140px;"> 		    
<div class="inner-search">
	<form action="index.php" accept-charset="utf-8" target="_blank" method="get">
    <input type="hidden" name="mod" value="tao" />
    <input type="hidden" name="act" value="view" />
    
    <div style="height:49px;*height:50px"></div>
    <div style="float:left; width:324px">&nbsp;</div>
    	<input type="text" autocomplete="off" value="" id="inner-offer-q" name="q" />
    	<input type="submit" id="inner-offer-submit" value="查返利" />
        <div class="url-how-to">
    		<span>如何获取商品网址？</span>
    		<div class="hover-tips hidden clearfix">
    			<p>在淘宝选好商品，复制商品详情页网址，粘贴到此即可！</p>
    			<img src="<?=TPLURL?>/images/search_tip.png" alt="商品地址" />
    			<s></s>
    		</div>
    	</div>
        <div style="clear:both"></div>
        <div style="position:relative">
        <div class="search-tips clearfix">
    		粘贴 <strong>商品网址</strong> 到这，如http://item.taobao.com/item.htm?id=123...
    		<s></s>
    	</div>
        </div>
        
        
	</form>
</div>
    		</div>
            <?php if(!empty($slides)){?>
        <div>
        <div class="jiaodian" style="margin-bottom:10px">
    <div id="KinSlideshow" style="visibility:hidden;">
      <?php foreach($slides as $row){?>
      <a href="<?=$row['url']?>" target="_blank"><img src="<?=$row['img']?>" alt="<?=$row['title']?>" width="948" height="90" /></a>
      <?php }?>
    </div>
</div>
        </div>
        <?php }?>
		<?php if(TAOTYPE==1){include(TPLURL.'/tao/catlist.tpl.php');}?>
        <?php if(TAOTYPE==2){include(TPLURL.'/tao/category.tpl.php');}?>
        <div class="taoright">
        <script>
        document.write('<iframe name="alimamaifrm" frameborder="0" marginheight="0" marginwidth="0" border="0" scrolling="no" width="230" height="212" src="page/chongzhi.php?pid=<?=$webset['taoapi']['taobao_chongzhi_pid']?>&uid=<?=$dduser['id']?>" ></iframe>');
        </script>
        <div class="ir_cx">
			<div class="ir_cxtt"><em>淘宝最热店铺</em></div>
			<div id="cxlist" class="ir_cxlist">
           <?php foreach($shops as $row){?>
				<dl>
					<dt class="i_border"><a href="<?=TAOTYPE==1?$row['detail_url']:$row['jump']?>" <?=tao_perfect_click($row['jump'])?> target="_blank"><img onerror="this.src='images/tbdp.gif'" src="<?=$row['logo']?>" alt="<?=$row['title']?>" /></a></dt>
					<dd><a href="<?=TAOTYPE==1?$row['detail_url']:$row['jump']?>" <?=tao_perfect_click($row['jump'])?>  target="_blank"><?=$row['title']?></a><span>最高返:<b><?=$row['fanxianlv']?></b>%</span></dd>
				</dl>
           <?php }?>
			</div>
		</div>
        </div>
 
</div>
<div style="clear:both"></div>
</div>
<?=AD(108)?>
<?php include(TPLPATH.'/footer.tpl.php');?>