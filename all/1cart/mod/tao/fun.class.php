<?php //多多
class dd_tao_class{
	public $duoduo;
	
	function __construct($duoduo){
		$this->duoduo=$duoduo;
	}
	
	function get_type($type='goods'){
		$type_all=dd_get_cache('type');
		$goods_type=$type_all[$type];
		return $goods_type;
	}

	function dd_tao_goods($data){ //如果cid为a，检索推荐数据
		$num=$data['num'];
		$frmnum=$data['frmnum']?$data['frmnum']:0;
		$cid=$data['cid'];
		$total=$data['total'];
		$where='1';
		if((int)$cid >0){
			$where.=' and cid="'.$cid.'"';
		}
		elseif($cid=='a'){
			$where.=' and tuijian="1"';
		}
		if($total==1){
			$total_num=$this->duoduo->count('goods',$where);
		}
    	$tao_goods=$this->duoduo->select_all('goods','*',$where.' order by sort desc,id desc limit '.$frmnum.','.$num);
		foreach($tao_goods as $k=>$row){
			if($row['promotion_price']>0){
				$commission=($row['promotion_price']/$row['price'])*$row['commission'];
			}else{
				$tao_goods[$k]['promotion_price']=$row['price'];
				$commission=$row['commission'];
			}
			$tao_goods[$k]['name']=$row['title'];
        	$tao_goods[$k]['fxje']=jfb_data_type(fenduan($commission,$this->duoduo->webset['fxbl'],$this->duoduo->dduser['level'],TBMONEYBL));
	       	$tao_goods[$k]['go_view']=u('tao','view',array('iid'=>$row['iid']));
			$tao_goods[$k]['gourl']=u('tao','view',array('iid'=>$row['iid']));
			$tao_goods[$k]['go_shop']=u('shop','list',array('nick'=>$row['nick']));
			$tao_goods[$k]['jump']='http://item.taobao.com/item.htm?id='.$row['iid'];
    	}
		
		if($total==1){
			return array('total'=>$total_num,'data'=>$tao_goods);
		}
		else{
			return $tao_goods;
		}
	}
	
	function dd_tao_shops($shops){ //处理淘宝店铺数据
		foreach ($shops as $k=>$row) {
			$row["logo"] = TAOLOGO . $row["pic_path"];
			if ($row['type'] == 'B') {
				$row['level'] == 21;
			}
			if ($row['level'] == 21) {
				$row['onerror'] = 'images/tbsc.gif';
				if ($row["pic_path"] == 'Array' || $row["pic_path"] == '') {
					$row["logo"] = 'images/tbsc.gif';
				}
			} else {
				$row['onerror'] = 'images/tbdp.gif';
				if ($row["pic_path"] == 'Array' || $row["pic_path"] == '') {
					$row["logo"] = 'images/tbdp.gif';
				}
			}
			if(TAOTYPE==1){
		    	$row['detail_url'] = "http://shop" . $row["sid"] . '.taobao.com';
				$row['jump'] = "index.php?mod=jump&act=shop&sid=" . $row["sid"] . '&pic=' . urlencode(base64_encode($row["logo"])) . '&fan=' . urlencode($row["fanxianlv"]) . '&name=' . urlencode($row["title"]).'&url=';
			}
			else{
		    	if (WEBTYPE == '0') {
					$row['jump'] = "index.php?mod=jump&act=shop&sid=" . $row["sid"] . '&url=' . urlencode(base64_encode($row["shop_click_url"])) . '&pic=' . urlencode(base64_encode($row["logo"])) . '&fan=' . urlencode($row["fanxianlv"]) . '&name=' . urlencode($row["title"]);
				} else {
					$row['jump'] = u('tao', 'shop', array (
						'nick' => $row["nick"]
					));
				}
			}
			$shops[$k]=$row;
		}
		return $shops;
	}
}
?>