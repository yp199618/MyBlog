<?php

namespace App\Http\Controllers\Index\WX;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\WX\ImageService;
use App\Services\Share\MakeCouponShareImageService;

class ImageController extends Controller
{
    public $repository;
    public $makeImage;

    public function __construct(ImageService $repository, MakeCouponShareImageService $img)
    {
      $this->repository = $repository;
      $this->makeImage = $img;
    }

    // 生成淘宝优惠券的分享图片
    public function couponShareImage($id, Request $request)
    {
      $itemInfo = $this->repository->itemInfo(['num_iids' => $id, 'platform' => '2']);
      $itemInfo == false ? abort(404) : '';
      $imageInfo = $this->repository->imageInfo($itemInfo, $request->all());
      $img = $this->makeImage->makeImage($imageInfo);

      return $img->response();
    }

    // 生成聚划算拼团的分享图片
    public function pintuanShareImage($id, Request $request)
    {
      $itemInfo = $this->repository->itemInfo(['num_iids' => $id, 'platform' => '2']);
      $itemInfo == false ? abort(404) : '';
      $imageInfo = $this->repository->imagePinTuanInfo($itemInfo, $request->all());
      $img = $this->makeImage->makePinTuanImage($imageInfo);

      return $img->response();
    }
}
