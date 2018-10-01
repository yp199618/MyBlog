<?php

//$url='http://vip.runker.net/list/?k=%E7%94%BB%E6%B1%9F%E6%B9%96';
    
    $url="http://vip.runker.net/list/?sid=eJxTitEvKYvRD_LxKkjyMM8JcvfI8YuI0TeI0Y8pNU1NM4spNbdMNI8pNTM1TgKyTc1MlQCe1w98";
    
	
	$html = file_get_contents($url);
	$html2=str_replace("行客","Yuri",$html);
    echo $html2;

    
?>