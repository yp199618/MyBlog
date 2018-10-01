<?php
$parameter=act_tao_view();
extract($parameter);

if($allow_fanli==1){
	include(TPLPATH.'/tao/view_goods.tpl.php');
}
else{
	include(TPLPATH.'/tao/view_list.tpl.php');
}
?>