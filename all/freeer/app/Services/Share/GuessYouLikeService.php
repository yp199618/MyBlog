<?php

namespace App\Services\Share;

use App\Repositories\Contracts\AlimamaRepositoryInterface;

/**
 * 好券清单API【导购】
 */
class GuessYouLikeService
{
  public $alimama;

  public function __construct(AlimamaRepositoryInterface $api)
  {
    $this->alimama = $api;
  }

  // 获取制定数量的优惠券数量，调用的是优惠券清单的api
  public function coupons($adzonId, $num)
  {
    $result = $this->alimama->taobaoTbkDgItemCouponGet(['adzone_id' => $adzonId, 'page_size' => $num, 'page_no' => mt_rand(10, 20)]);

    if (empty($result)) {
      return [];
    }

    return $result;
  }

  // 获取指定数量的拼团的数量，调用数据随机
  public function pintuan($adzonId, $num)
  {
      $result = $this->alimama->taobaoTbkDgOptimusMaterial(['adzone_id' => $adzonId, 'page_size' => $num, 'page_no' => mt_rand(10, 30), 'material_id' => '4071']);

      return empty($result) ? [] : $result;
  }
}
