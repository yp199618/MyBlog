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
$type_all=dd_get_cache('type');
$goods_type=$type_all['goods'];
$cid_arr1['']='全部';
$cid_arr=$cid_arr1+$goods_type;

include(ADMINTPL.'/header.tpl.php');
?>
<form name="form1" action="" method="get">
<table cellspacing="0" width="100%" style="border:1px  solid #DCEAF7; border-bottom:0px; background:#E9F2FB">
        <tr>
              <td width="115px" class="bigtext">&nbsp;<img src="images/arrow.gif" width="16" height="22" align="absmiddle" /> <a href="<?=u(MOD,'addedi')?>" class="link3">[添加商品]</a></td>
              <td width="" align="right">商品名称：<input type="text" name="q" value="<?=$q?>" />&nbsp;<?=select($cid_arr,$cid,'cid')?><input type="submit" value="提交" /></td>
              <td width="125px" align="right" class="bigtext">共有 <b><?php echo $total;?></b> 条记录&nbsp;&nbsp;</td>
            </tr>
      </table>
      <input type="hidden" name="mod" value="<?=MOD?>" />
      <input type="hidden" name="act" value="<?=ACT?>" />
      </form>
      <form name="form2" method="get" action="" style="margin:0px; padding:0px">
      <table id="listtable" border=1 cellpadding=0 cellspacing=0 bordercolor="#dddddd">
        <tr>
          <th width="3%" ><input type="checkbox" onClick="checkAll(this,'ids[]')" /></th>
          <th width="">商品名称</th>
          <th width="70px">分类</th>
          <th width="115px">掌柜</th>
          <th width="8%">价格</th>
		  <th width="8%">佣金</th>
          <th width="7%">图片</th>
          <th width="6%"><a href="<?=u(MOD,'list',array('sort'=>desc))?>">排序</a></th>
          <th width="140px">添加时间</th>
          <th width="8%">执行操作</th>
        </tr>
		<?php foreach ($row as $r){?>
	    <tr>
          <td><input type='checkbox' name='ids[]' value='<?=$r["id"]?>' id='content_<?=$r["id"]?>' /></td>
          <td><?=$r["title"]?></td>
          <td><?=$goods_type[$r["cid"]]?></td>
		  <td><?=$r["nick"]?></td>
		  <td><?=$r["price"]?></td>
          <td><?=$r["commission"]?></td>
          <td class="showpic" pic="<?=$r['pic_url']?>_b.jpg">查看</td>
          <td><?=$r["sort"]?></td>
          <td><?=date('Y-m-d H:i:s',$r["addtime"])?></td>
		  <td><a href="<?=u(MOD,'addedi',array('id'=>$r['id']))?>" class=link4>查看</a></td>
		</tr>
		<?php }?>
        </table>
        <div style="position:relative; padding-bottom:10px">
            <input type="hidden" name="mod" value="<?=MOD?>" /><input type="hidden" name="act" value="del" />
            <div style="position:absolute; left:5px; top:5px"><input type="submit" value="删除" class="myself" onclick='return confirm("确定要删除?")'/></div>
            <div class="megas512" style=" margin-top:15px;"><?=pageft($total,$pagesize,u(MOD,'list',array('q'=>$q,'sort'=>$sort)));?></div>
            </div>
       </form>
<?php include(ADMINTPL.'/footer.tpl.php');?>