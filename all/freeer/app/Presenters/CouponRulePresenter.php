<?php

namespace App\Presenters;

class CouponRulePresenter
{
  public function showCat($couponRule)
  {
    empty($couponRule->cat) ? $catStr = '' : $catStr = $couponRule->cat;

    return $catStr;
  }

  public function showPageSize($couponRule)
  {
    empty($couponRule->page_size) ? $pageSizeStr = '' : $pageSizeStr = $couponRule->page_size;

    return $pageSizeStr;
  }

  public function showQ($couponRule)
  {
    empty($couponRule->q) ? $qStr = '' : $qStr = $couponRule->q;

    return $qStr;
  }

  public function showSelected($input, $optionValue)
  {
    if (!empty($input) && $input == $optionValue) {
      return 'selected';
    }

    return '';
  }

  public function showChecked($input, $optionValue)
  {
    if (!empty($input) && $input == $optionValue) {
      return 'checked';
    }

    return $input;
  }
}
