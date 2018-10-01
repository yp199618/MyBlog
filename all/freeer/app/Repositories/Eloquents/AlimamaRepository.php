<?php

namespace App\Repositories\Eloquents;

use App\Repositories\Contracts\AlimamaRepositoryInterface;
use Longbeidou\Taobaoke\Contracts\Contract;
use App\Traits\CouponRelated;

/**
 * 阿里妈妈淘宝客api实现
 */
class AlimamaRepository implements AlimamaRepositoryInterface
{
  use CouponRelated;

  public $alimamaSdk;

  public function __construct(Contract $api)
  {
    $this->alimamaSdk = $api;
  }

  // 好券清单api
  public function taobaoTbkDgItemCouponGet(Array $para)
  {
    $result = $this->alimamaSdk->taobaoTbkDgItemCouponGet($para);

    if (empty($result->results)) {
      return false;
    }

    if ($para['page_size'] != '100' && count($result->results->tbk_coupon) == 100) {
      return false;
    }

    if ($para['page_size'] == '100' && !empty($para['q']) && !empty($para['page_no']) && $para['page_no'] != '1') {
      return false;
    }

    return $result->results->tbk_coupon;
  }

  // 通用物料搜索API（导购）
  public function taobaoTbkDgMaterialOptional(Array $para)
  {
    $result = $this->alimamaSdk->taobaoTbkDgMaterialOptional($para);

    if (empty($result->result_list)) {
      return false;
    }

    return $result->result_list->map_data;
  }

  // 获取聚划算的信息
  public function taobaoJuItemsSearch(Array $para)
  {
    $juItems = $this->alimamaSdk->taobaoJuItemsSearch($para);

    if (empty($juItems->result->model_list->items)) {
      return false;
    }

    return $juItems->result->model_list->items;
  }

  // 查询解析淘口令
  public function taobaoWirelessShareTpwdQuery(String $tpwd)
  {
    $result = $this->alimamaSdk->taobaoWirelessShareTpwdQuery($tpwd);

    if (empty($result->content)) {
      return false;
    }

    return $result;
  }

  // 淘抢购api
  public function taobaoTbkJuTqgGet(Array $para)
  {
    $result = $this->alimamaSdk->taobaoTbkJuTqgGet($para);

    if (empty($result->results)) {
      return false;
    }

    return $result->results->results;
  }

  // 淘宝客商品详情（简版）
  public function taobaoTbkItemInfoGet(Array $datas)
  {
    $result = $this->alimamaSdk->taobaoTbkItemInfoGet($datas);

    if (empty($result->results->n_tbk_item)) {
      return false;
    }

    return $result->results->n_tbk_item[0];
  }

  // * 阿里妈妈推广券信息查询
  public function taobaoTbkCouponGet(Array $datas)
  {
    $result = $this->alimamaSdk->taobaoTbkCouponGet($datas);

    if (empty($result->data)) {
      return false;
    }

    return $result->data;
  }

  // 生成淘口令
  public function taobaoWirelessShareTpwdCreate(Array $datas)
  {
    $result = $this->alimamaSdk->taobaoWirelessShareTpwdCreate($datas);
    empty($result->model) ? $tpwd = false : $tpwd = $result->model;

    return $tpwd;
  }

  //淘宝客擎天柱通用物料API  淘宝客物料下行-导购
  public function taobaoTbkDgOptimusMaterial(Array $datas)
  {
    $result = $this->alimamaSdk->taobaoTbkDgOptimusMaterial($datas);

    return empty($result->result_list->map_data) ? false : $result->result_list->map_data;
  }
}
