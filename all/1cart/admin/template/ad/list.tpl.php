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

if($_GET['do']=='update'){
	$a=$duoduo->select_all('ad','*');
	foreach($a as $arr){
		$id=$arr['id'];
		if($arr['content']==''){
			$c='<a target="_blank" ';
			if($arr['link']!=''){
				$c.='href="'.$arr['link'].'"';
			}
			$c.='><img src="'.$arr['img'].'" ';
			if($arr['height']!=''){
				$c.='height="'.$arr['height'].'" ';
			}
			if($arr['width']!=''){
				$c.='width="'.$arr['width'].'" ';
			}
			$c.='alt="'.$arr['title'].'" /></a>';
		
			if($arr['type']==1){
				$js="document.write('".$c."')";
				create_file(DDROOT.'/data/ad/'.$id.'.js',$js);
			}
			else{
				$ad_content=$c;
			}
    	}
		else{
			if($arr['type']==1){
				$js_ad='';
				$a=explode("\r\n",$arr['content']);
				foreach($a as $v){
					$v=preg_replace('#\s+\/\/(.*)#','',$v);
					$v=str_replace('<!--','',$v);
					$js_ad=$js_ad.' '.$v;
				}
				$js='document.write("'.$js_ad.'")';
				create_file(DDROOT.'/data/ad/'.$id.'.js',$js);
			}
			else{
				$ad_content=$arr['content'];
				$ad_content=strtr($ad_content,array('\"'=>'"',"\'"=>"'"));
			}
		}	
	
		$data=array('edate'=>$arr['edate'],'width'=>$arr['width'],'height'=>$arr['height'],'img'=>$arr['img']?1:0,'content'=>$arr['content']?1:0,);
		if($arr['type']==2){
			$data['ad_content']=$ad_content;
		}
		dd_set_cache('ad/'.$id,$data);
	}
	jump(-1,'更新完成');
}

include(ADMINTPL.'/header.tpl.php');
?>
<form name="form1" action="" method="get">
<table cellspacing="0" width="100%" style="border:1px  solid #DCEAF7; border-bottom:0px; background:#E9F2FB">
        <tr>
              <td width="20%" class="bigtext">&nbsp;<img src="images/arrow.gif" width="16" height="22" align="absmiddle" />&nbsp;<a href="<?=u(MOD,'addedi')?>" class="link3">[新增站点广告]</a>&nbsp;<a href="<?=u(MOD,'list',array('do'=>'update'))?>" class="link3">[更新缓存]</a> </td>
              <td width="" align="right">标题：<input type="text" name="q" value="<?=$q?>" />&nbsp;<input type="submit" value="提交" /></td>
              <td width="150px" align="right">共有 <b><?php echo $total;?></b> 条记录&nbsp;&nbsp;</td>
            </tr>
      </table>
      <input type="hidden" name="mod" value="<?=MOD?>" />
      <input type="hidden" name="act" value="<?=ACT?>" />
      </form>
      <form name="form2" method="get" action="" style="margin:0px; padding:0px">
      <table id="listtable" border=1 cellpadding=0 cellspacing=0 bordercolor="#dddddd">
                    <tr>
                      <th width="3%"><input type="checkbox" onClick="checkAll(this,'ids[]')" /></th>
                      <th width="3%">ID</th>
					  <th width="150px">标题</th>
                      <th width="">说明</th>
                      <th width="140px">到期时间</th>
                      <th width="140px">添加时间</th>
                      <th width="26%">执行操作</th>
                    </tr>
					<?php foreach ($row as $r){?>
					  <tr>
                        <td><input <?php if($r['sys']==1){?>title="系统数据，不准删除"  disabled="disabled"<?php }?> type='checkbox' name='ids[]' value='<?=$r["id"]?>' id='content_<?=$r["id"]?>' /></td>
                        <td><?=$r["id"]?></td>
						<td><?=$r["title"]?></td>
                        <td class="input" field='adtype' w='135' tableid="<?=$r["id"]?>" status='a'><?=$r["adtype"]?></td>
                        <td><?=date('Y-m-d',$r["edate"])?></td>
						<td><?=date('Y-m-d H:i:s',$r["addtime"])?></td>
						<td><a href="<?=u('ad','addedi',array('id'=>$r['id']))?>" class=link4>修改</a> <A href="javascript:copy('&lt?=AD(<?=$r["id"]?>)?&gt;')" class=link4>获取广告代码</a>：<input style="width:90px; font-family:'宋体'" value="&lt?=AD(<?=$r["id"]?>)?&gt;" type="text" /></td>
					  </tr>
					<?php }?>
                  </table>
        <div style="position:relative; padding-bottom:10px">
            <input type="hidden" name="mod" value="<?=MOD?>" /><input type="hidden" name="act" value="del" />
            <div style="position:absolute; left:5px; top:5px"><input type="submit" value="删除" onclick='return confirm("确定要删除?")'/></div>
            <div class="megas512" style=" margin-top:15px;"><?=pageft($total,$pagesize,u(MOD,'list',array('q'=>$q)));?></div>
            </div>
       </form>
<?php include(ADMINTPL.'/footer.tpl.php');?>