<!--横向滑动商品导航 开始-->
<header class="mui-bar mui-bar-nav lbd-index-nav" id="lbd-index-header">
  <div class="mui-scroll-wrapper mui-slider-indicator mui-segmented-control mui-segmented-control-inverted">
        <div class="mui-scroll" id="lbd-index-scroll">
            <a class="mui-control-item mui-active" title="淘宝天猫优惠券" href="{{ route('wx.index') }}">推荐</a>
            @foreach($topGoodsCategory as $category)
            <a title="{{ $category->name }}淘宝天猫优惠券商品" class="mui-control-item" href="{{  route('goodsCategorys.categoryOne', $category->id) }}">{{ $category->name }}</a>
            @endforeach
        </div>
        <a class="mui-icon mui-icon-arrowdown mui-pull-right" id="lbd-mask-cate-show"></a>
   </div>
</header><!--横向滑动商品导航 结束-->
