<?php
// return file_get_contents("http://thinkphp.com/index/index/create");
//初始化  
$curl = curl_init();  
//设置抓取的url  
curl_setopt($curl, CURLOPT_URL, 'https://www.okcoin.com/api/v1/ticker.do?symbol=ltc_usd');  
//设置头文件的信息作为数据流输出  
curl_setopt($curl, CURLOPT_HEADER, 1);  
//设置获取的信息以文件流的形式返回，而不是直接输出。  
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
//执行命令  
$data = curl_exec($curl);  
echo curl_getinfo($curl,CURLINFO_HTTP_CODE); //输出请求状态码  
//关闭URL请求  
curl_close($curl);  
//显示获得的数据  
print_r($data);
?>