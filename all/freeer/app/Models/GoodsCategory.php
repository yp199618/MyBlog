<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsCategory extends Model
{
  protected $table = "goods_categorys";

  protected $fillable = [
    'name', 'parent_id', 'path', 'order', 'is_shown', 'is_recommended', 'font_icon', 'image'
  ];

  protected $hidden = [
  ];

  // 与优惠券规则建立一对一关系
  public function couponRule()
  {
    return $this->hasOne('App\Models\TaobaoTbkDgMaterialOptional', 'goods_category_id');
  }

  // 与通用搜索规则建立一对一关系
  public function dgMaterialOptionalRule()
  {
    return $this->hasOne('App\Models\TaobaoTbkDgMaterialOptional', 'goods_category_id');
  }
}
