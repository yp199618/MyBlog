<?php

namespace App\Services\Api;

use App\Repositories\Contracts\AlimamaRepositoryInterface;

class AlimamaService
{
  public $alimamaRepository;

  function __construct(AlimamaRepositoryInterface $alimama)
  {
    $this->alimamaRepository = $alimama;
  }

  // 获取收益优惠券的信息
  public function taobaoTbkDgItemCouponGet($para)
  {
    $couponItems = $this->alimamaRepository->taobaoTbkDgItemCouponGet($para);

    return $couponItems;
  }

  // 通用搜索 导购
  public function taobaoTbkDgMaterialOptional($para)
  {
    $couponItems = $this->alimamaRepository->taobaoTbkDgMaterialOptional($para);

    return $couponItems;
  }

  // 获取聚划算的信息
  public function taobaoJuItemsSearch($para)
  {
    $juItems = $this->alimamaRepository->taobaoJuItemsSearch($para);

    return $juItems;
  }

  // 淘抢购api
  public function taobaoTbkJuTqgGet(Array $para)
  {
    $tqgItems = $this->alimamaRepository->taobaoTbkJuTqgGet($para);

    return $tqgItems;
  }

  // 淘宝客物料下行
  public function taobaoTbkDgOptimusMaterial(Array $para)
  {
     return $this->alimamaRepository->taobaoTbkDgOptimusMaterial($para);
  }
}
