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

include(ADMINTPL.'/header.tpl.php');
?>
<div class="explain-col">
提示：请选择获取交易的时间段。
<b style="color:red">
<?=$lianmeng[$do]['gettip']?>
</b>
<br />已经获取过的交易不会覆盖原有的数据，获取过程将自动忽略。时间必须为8位，并且开始时间小于结束时间，否则将无法获取成功！<br />
</div>
<br />
<form action="index.php?mod=<?=MOD?>&act=<?=ACT?>&do=<?=$do?>" name="form1" method="post">
<table id="addeditable" border=1 cellpadding=0 cellspacing=0 bordercolor="#dddddd">
  <tr>
    <td width="115px" align="right">时间范围：</td>
    <td>&nbsp;<input name="sday" type="text" id="sday" size="10" maxlength="8" value="<?=date('Ymd')?>" /> 到 <input name="eday" type="text" id="eday" size="10" maxlength="8" value="<?=date('Ymd')?>" /></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td>&nbsp;<input type="submit" class="sub" name="sub" value="获取交易记录" /></td>
  </tr>
</table>
</form>
<?php include(ADMINTPL.'/footer.tpl.php');?>