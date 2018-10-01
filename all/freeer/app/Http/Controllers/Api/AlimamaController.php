<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Api\AlimamaService;

class AlimamaController extends Controller
{
    public $repository;

    public function __construct(AlimamaService $alimama)
    {
      $this->repository = $alimama;
    }

    // 好券清单的api
    public function taobaoTbkDgItemCouponGet(Request $request)
    {
      if (empty($request->adzone_id) || empty($request->page_size) || empty($request->page_no)) {
        return 415;
      }

      $result = $this->repository->taobaoTbkDgItemCouponGet($request->all());

      if (!$result) {
        return 415;
      }

      return $result;
    }

    // 通用物料搜索API（导购）
    public function taobaoTbkDgMaterialOptional(Request $request)
    {
      if (empty($request->adzone_id) || empty($request->page_size) || empty($request->page_no) || empty($request->q)) {
        return 415;
      }

      $paraArr = $request->all();

      foreach ($paraArr as $key => $para) {
        if ($para === null) {
          unset($paraArr[$key]);
        }
      }

      $result = $this->repository->taobaoTbkDgMaterialOptional($request->all());

      if (!$result) {
        return 415;
      }

      return $result;
    }

    // 聚划算的api
    public function taobaoJuItemsSearch(Request $request)
    {
      if (empty($request->pid) || empty($request->page_size) || empty($request->current_page)) {
        return 415;
      }

      $result = $this->repository->taobaoJuItemsSearch($request->all());

      if (!$result) {
        return 415;
      }

      return $result;
    }

    // 淘抢购
    public function taobaoTbkJuTqgGet(Request $request)
    {
      if (empty($request->adzone_id) || empty($request->page_size) || empty($request->start_time) || empty($request->end_time)) {
        return 415;
      }

      $result = $this->repository->taobaoTbkJuTqgGet($request->all());

      if (!$result) {
        return 415;
      }

      return $result;
    }

    // 淘宝客物料下行-导购
    public function taobaoTbkDgOptimusMaterial(Request $request)
    {
      if (empty($request->page_size) || empty($request->adzone_id) || empty($request->page_no) || empty($request->material_id)) {
        return 415;
      }

      $items = $this->repository->taobaoTbkDgOptimusMaterial($request->all());

      return $items ? $items : 415;
    }
}
