<?php
$parameter=act_user_tradelist();
extract($parameter);
$mall_status_arr=array(0=>'未确认',1=>'确认',-1=>'无效');
$css[]=TPLURL.'/css/usercss.css';
$css[]='css/qqFace.css';
include(TPLPATH."/header.tpl.php");
$shai_tip=array(0=>'<b class="shai" style="cursor:pointer; color:green; font-weight:normal">晒单</b>',1=>'<b style=" color:#5DDC4A; font-weight:normal">已晒单</b>');
?>
<script>
function checkTradelist(){
    var q=document.getElementById('q').value;
	if(q=='输入订单号' || q==''){
	    alert('订单号不能为空');
		return false;
	}
}
</script>
<div class="mainbody">
	<div class="mainbody1000">
    <?php include(TPLPATH."/user/top.tpl.php");?>
    	<div class="adminmain">
        	<div class="adminleft">
                <?php include(TPLPATH."/user/left.tpl.php");?>
            </div>
        	<div class="adminright">
                <?php include(TPLPATH."/user/notice.tpl.php");?>
                <div class="admin_searchx">
                <form action="index.php" onsubmit="return checkTradelist()">
                <input type="hidden" name="mod" value="user" /><input type="hidden" name="act" value="tradelist" />
                  <input class="admin_searchx_input1" type="text" id="q" name="q" value=" 输入订单号" onclick="this.value=''" />
                  <select name="do" style="margin-left:10px;font-size:12px;"><option value="lost">淘宝订单</option><option value="paipailost">拍拍订单</option><option value="malllost">商城订单</option></select><input class="admin_searchx_b" type="submit" value="查找遗漏订单" style="margin-left:10px; font-size:12px;" /></form>
                </div>
                <div class="admin_xfl">
                    <ul>
                    <li id="taobao"><a href="<?=u('user','tradelist',array('do'=>'taobao'))?>">我的淘宝订单</a> </li>
                    <li id="paipai"><a href="<?=u('user','tradelist',array('do'=>'paipai'))?>">我的拍拍订单</a> </li>
                    <li id="mall"><a href="<?=u('user','tradelist',array('do'=>'mall'))?>">我的商城订单</a> </li>
                    <li id="checked"><a href="<?=u('user','tradelist',array('do'=>'checked'))?>">待审核订单</a> </li>
                    </ul>
              	</div>
                <script>$('.admin_xfl ul #<?=$do?>').addClass('admin_xfl_xz');</script>
                <div class="admin_table">
                    <table width="770" border="0" cellpadding="0" cellspacing="1">
                    <?php if($do=='taobao'){?>
                      <tr>
                        <th width="120" height="26">订单号</th>
                        <th>宝贝名称</th>
                        <th width="73">金额</th>
                        <th width="70">返</th>
                        <th width="80">下单时间</th>
                        <th width="60">操作</th>
                      </tr>
                      <?php foreach ($dingdan as $r){?>
                      <?php if(TAOTYPE==2){?>
                      <tr>
                        <td height="33"><?=$r["trade_id"]?></td>
                        <td><?=$r["item_title"]?></td>
                        <td><?=$r["real_pay_fee"]?></td>
                        <td><?=$r["jifenbao"]>0?jfb_data_type($r["jifenbao"]).TBMONEY:$r["fxje"].'元'?></td>
                        <td><?=date('Y-m-d',strtotime($r["pay_time"]))?></td>
                        <?php if($duoduo->no_pay_trade($r["pay_time"],$r["jifenbao"],$r["fxje"])==1){?>
                        <td><span style="color:#F00">未结算</span></td>
                        <?php }elseif($r["pay_time"]>=$webset['baobei']['shai_s_time']){?>
                        <td trade_id='<?=$r["id"]?>' iid='<?=$r["num_iid"]?>'><?=$shai_tip[$r["baobei_id"]?1:0]?></td>
                        <?php }else{?>
                        <td>--</td>
                        <?php }?>
                      </tr>
                      <?php }else{if($r["real_pay_fee"]==0){$r["real_pay_fee"]=$r["pay_price"];}?>
                      <tr>
                        <td height="33"><?=$r["trade_id"]?></td>
                        <td><?=$r["item_title"]?></td>
                        <td><?=$r["real_pay_fee"]>0?$r["real_pay_fee"]:'等待结算'?></td>
                        <?php if($r["status"]==4){?>
                        <td><span style="color:red">无</span></td>
                        <?php }elseif($r['addtime']>0){?>
                        <td><?=$r["jifenbao"]>0?jfb_data_type($r["jifenbao"]).TBMONEY:'等待结算'?></td>
                        <?php }else{?>
                        <td><?=$r["jifenbao"]>0?jfb_data_type($r["jifenbao"]).TBMONEY:$r["fxje"].'元'?></td>
                        <?php }?>
                        <td><?=date('Y-m-d',strtotime($r["create_time"]>0?$r["create_time"]:$r["pay_time"]))?></td>
                        <?php if($r["status"]==4){?>
                        <td>--</td>
                        <?php }elseif($duoduo->no_pay_trade($r["pay_time"],$r["jifenbao"],$r["fxje"])==1){?>
                        <td><span style="color:#F00">未结算</span></td>
                        <?php }elseif($r["pay_time"]>=$webset['baobei']['shai_s_time']){?>
                        <?php if($r["checked"]==2){?>
                        <td trade_id='<?=$r["id"]?>' iid='<?=$r["num_iid"]?>'><?=$shai_tip[$r["baobei_id"]?1:0]?></td>
                        <?php }else{?>
                        <td >待审核</td>
                        <?php }?>
                        <?php }else{?>
                        <td>--</td>
                        <?php }?>
                      </tr>
                      <?php }?>
                      <?php }?>
                    <?php }?>
                    
                    <?php if($do=='lost'){?>
                      <tr>
                        <th width="120" height="26">订单号</th>
                        <th>宝贝名称</th>
                        <?php if(TAOTYPE==1){?>
                        <th width="60">单价</th>
                        <th width="60">数量</th>
                        <?php }else{?>
                        <th width="60">金额</th>
                        <th width="60">返</th>
                        <?php }?>
                        <th width="80">下单时间</th>
                        <th width="80">找回订单</th>
                      </tr>
                      <?php foreach ($dingdan as $r){?>
                      <?php if(TAOTYPE==2){?>
                      <tr>
                        <td height="33"><?=$r["trade_id"]?></td>
                        <td><?=$r["item_title"]?></td>
                        <td><?=$r["real_pay_fee"]?></td>
                        <td><?=$r["jifenbao"]>0?jfb_data_type($r["jifenbao"]).TBMONEY:$r["fxje"].'元'?></td>
                        <td><?=date('Y-m-d',strtotime($r["pay_time"]))?></td>
                        <td><a href="<?=u('user','confirm',array('do'=>'tao',id=>$r["id"]))?>"><img src="images/queren.gif" width="86" height="20" title="我要确认这份订单" border=0 /></a></td>
                      </tr>
                      <?php }else{?>
                      <tr>
                        <td height="33"><?=$r["trade_id"]?></td>
                        <td><?=$r["item_title"]?></td>
                        <td><?=$r["pay_price"]?></td>
                        <td><?=$r["item_num"];?></td>
                        <td><?=date('Y-m-d',strtotime($r["create_time"]>0?$r["create_time"]:$r["pay_time"]))?></td>
                        <?php if(in_array($r["id"],$ziguanlian)){?>
                        <td>同一购物车</td>
                        <?php }else{?>
                        <td><a href="<?=u('user','confirm',array('do'=>'tao',id=>$r["id"]))?>"><img src="images/queren.gif" width="86" height="20" title="我要确认这份订单" border=0 /></a></td>
                        <?php }?>
                      </tr>
                      <?php }?>
                      <?php }?>
                    <?php }?>
                    
                    <?php if($do=='checked'){?>
                      <tr>
                        <th width="120" height="26">订单号</th>
                        <th>宝贝名称</th>
                        <th width="60">金额</th>
                        <th width="60">返</th>
                        <th width="80">成交时间</th>
                      </tr>
                      <?php foreach ($dingdan as $r){?>
                      <?php if(TAOTYPE==2){?>
                      <tr>
                        <td height="33"><?=$r["trade_id"]?></td>
                        <td><?=$r["item_title"]?></td>
                        <td><?=$r["real_pay_fee"]?></td>
                        <td><?=$r["jifenbao"]>0?jfb_data_type($r["jifenbao"]).TBMONEY:$r["fxje"].'元'?></td>
                        <td><?=date('Y-m-d',strtotime($r["pay_time"]))?></td>
                      </tr>
                      <?php }else{?>
                      <tr>
                        <td height="33"><?=$r["trade_id"]?></td>
                        <td><?=$r["item_title"]?></td>
                        <?php if($r["status"]==5){?>
                        <td><?=$r["real_pay_fee"]?></td>
                        <td><?=$r["jifenbao"]>0?jfb_data_type($r["jifenbao"]).TBMONEY:$r["fxje"]?></td>
                        <?php }else{?>
                        <td>等待结算</td>
                        <td>等待结算</td>
                        <?php }?>
                        <td><?=date('Y-m-d',strtotime($r["pay_time"]))?></td>
                      </tr>
                      <?php }?>
                      <?php }?>
                    <?php }?>
                    
                    <?php if($do=='paipai'){?>
                      <tr>
                        <th width="120" height="26">订单号</th>
                        <th>宝贝名称</th>
                        <th width="73">金额</th>
                        <th width="43">数量</th>
                        <th width="59">返现</th>
                        <th width="80">成交时间</th>
                        
                      </tr>
                      <?php foreach ($dingdan as $r){?>
                      <tr>
                        <td height="33"><?=$r["dealId"]?></td>
                        <td><?=$r["commName"]?></td>
                        <td><?=$r["careAmount"]?></td>
                        <td><?=$r["commNum"]?></td>
                        <td><?=$r["fxje"]?></td>
                        <td><?=date('Y-m-d',$r["chargeTime"])?></td>
                      </tr>
                      <?php }?>
                    <?php }?>
                    
                    <?php if($do=='paipailost'){?>
                      <tr>
                        <th width="120" height="26">订单号</th>
                        <th>宝贝名称</th>
                        <th width="60">金额</th>
                        <th width="60">返现</th>
                        <th width="80">成交时间</th>
                        <th width="80">找回订单</th>
                      </tr>
                      <?php foreach ($dingdan as $r){?>
                      <tr>
                        <td height="33"><?=$r["dealId"]?></td>
                        <td><?=$r["commName"]?></td>
                        <td><?=$r["careAmount"]?></td>
                        <td><?=$r["fxje"]?></td>
                        <td><?=date('Y-m-d',$r["chargeTime"])?></td>
                        <td><a href="<?=u('user','confirm',array('do'=>'paipai',id=>$r["id"]))?>"><img src="images/queren.gif" width="86" height="20" title="我要确认这份订单" border=0 /></a></td>
                      </tr>
                      <?php }?>
                    <?php }?>
                    
                    <?php if($do=='mall'){?>
                      <tr>
                        <th height="26">订货号</th>
                        <th width="120">下单商城</th>
                        <th width="60">数量</th>
                        <th width="60">单价</th>
                        <th width="80">返现</th>
                        <th width="60">积分</th>
                        <th width="150">成交时间</th>
                        <th width="100">交易状态</th>
                      </tr>
                      <?php foreach ($dingdan as $r){?>
                      <tr>
                        <td height="33"><?=$r["order_code"]?></td>
                        <td><?=$r["mall_name"]?></td>
                        <td><?=$r["item_count"]?></td>
                        <td><?=$r["item_price"]?></td>
                        <td><?=$r["fxje"]?></td>
                        <td><?=$r["jifen"]?></td>
                        <td><?=date('Y-m-d H:i:s',$r["order_time"])?></td>
                        <td><?=$mall_status_arr[$r["status"]]?></td>
                      </tr>
                      <?php }?>
                    <?php }?>
                    
                    <?php if($do=='malllost'){?>
                      <tr>
                        <th height="26">订货号</th>
                        <th width="120">下单商城</th>
                        <th width="80">成交数量</th>
                        <th width="60">单价</th>
                        <th width="120">返现</th>
                        <th width="150">成交时间</th>
                        <th width="100">找回订单</th>
                      </tr>
                      <?php foreach ($dingdan as $r){?>
                      <tr>
                        <td height="33"><?=$r["order_code"]?></td>
                        <td><?=$r["mall_name"]?></td>
                        <td><?=$r["item_count"]?></td>
                        <td><?=$r["item_price"]?></td>
                        <td><?=$r["fxje"]?></td>
                        <td><?=date('Y-m-d H:i:s',$r["order_time"])?></td>
                        <td><a href="<?=u('user','confirm',array('do'=>'mall',id=>$r["id"]))?>"><img src="images/queren.gif" width="86" height="20" title="我要确认这份订单" border=0 /></a></td>
                      </tr>
                      <?php }?>
                    <?php }?>
                    </table>
                    <?php if($total==0){?>
                    <div style="margin-top:25px; text-align:center">暂无数据</div>
                    <?php }?>
                </div>
                <div class="megas512" style="clear:both"><?=pageft($total,$pagesize,u(MOD,ACT,array('do'=>$do)));?></div>
                
                <div class="adminright_yuye">
                    <div class="tishitubiao"></div>
                    <?php if($do=='taobao'){?>
                    <p>亲！淘宝订单需要确认收货后，<?=WEBNICK?>最晚隔天会收到数据反馈！</p>
                    <?php }elseif($do=='mall'){?>
                    <p>亲！商城订单大约需要2个月才会核对有效，请耐心等待！</p>
                    <?php }elseif($do=='paipai'){?>
                    <p>亲！拍拍订单需要确认收货后，<?=WEBNICK?>最晚隔天会收到数据反馈！</p>
                    <?php }elseif($do=='lost' || $do=='checked'){?>
                    <p>亲！没有确认收货的订单不能显示价格和返利哦！</p>
                    <?php }?>
                </div>
                
                <?php if($do=='taobao' && ($webset['taoapi']['freeze']==2 || $webset['taoapi']['freeze']==1)){?>
                <?php if($webset['taoapi']['freeze_limit']>0){$w='返利大于'.$webset['taoapi']['freeze_limit'].TBMONEY;}?>
                <div class="adminright_yuye">
                    <div class="tishitubiao"></div>
                    <?php if($webset['taoapi']['freeze']==2){?>
                    <p>亲！16天内<?=$w?>的淘宝订单处于未核对状态。</p>
                    <?php }?>
                </div>
                <?php }?>
            </div>
    	</div>
  </div>
</div>
<?php
include(TPLPATH."/baobei/share.tpl.php");
include(TPLPATH."/footer.tpl.php");
?>