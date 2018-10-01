<?php

//$url='http://vip.runker.net/list/?k=%E7%94%BB%E6%B1%9F%E6%B9%96';
    
    $url="http://api.huobipro.com/market/history/kline?period=1min&size=2&symbol=btcusdt";
    $html = file_get_contents($url);
    echo $html;

    
?>