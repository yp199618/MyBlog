@inject('couponShow', 'App\Presenters\CouponListPresenter')
@foreach($couponItems as $key => $item)
  @if(!empty($item->coupon_info))
  <li class="mui-table-view-cell mui-media">
    <a class="addPara" no='data{{ $key }}' href="{{ route('wx.itemInfo.iteminfo') }}/{{ $item->num_iid }}">
      <data id="data{{ $key }}" link='{{ $couponShow->getParaStrFromUrl($item->coupon_share_url) }}' coupon="coupon_info={{ $item->coupon_start_time }}and{{ $item->coupon_end_time }}and{{ $couponShow->saveMoney($item->coupon_info, $item->zk_final_price) }}"></data>
      <div class="mui-row">
        <div class="mui-col-xs-4 goods-image">
          <img data-lazyload="{{ $item->pict_url }}"/>
        </div>
        <div class="mui-col-xs-8 lbd-content">
          <p class="lbd-title">{{ $item->title }}</p>
        </div>
        <div class="mui-col-xs-8 lbd-content-more">
          <div class="lbd-top">
            <!-- <span class="lbd-from-tmall">天猫</span> -->
            {!! $couponShow->showTmallOrTaobao($item->user_type) !!}
            <span class="lbd-from-new">今日上新</span>
            销量：{{ $item->volume }}
          </div>
          <div class="lbd-bottom">
            <div class="mui-pull-left">
              <div class="lbd-price-ori">原价￥{{ number_format($item->zk_final_price, 2) }}</div>
              <div class="lbd-price-now"><span class="lbd-m">￥</span>{{ $couponShow->finalPrice($item->coupon_info, $item->zk_final_price) }}</div>
            </div>
            <div class="mui-pull-right">
              <div class="lbd-coupon">
                <div class="lbd-left-circle"></div>
                <div class="lbd-right-circle"></div>
                <span class="lbd-m">￥</span>{{ $couponShow->saveMoney($item->coupon_info, $item->zk_final_price, 0) }}元券
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </li>
  @endif
@endforeach
