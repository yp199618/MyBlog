<?php
    header('Content-type:text/html; charset=utf-8');
    // 关闭错误报告
    error_reporting(0);
    
    if (file_exists("local_db.ini")){
        $data = parse_ini_file("local_db.ini",true);
        //echo $data['url'];
    }else{
        echo "加载数据库配置文件失败";
    }
    
    // 创建连接
    $conn = new mysqli($data['url'], $data['user'],$data['password'], $data['dbName']);
    mysqli_set_charset($conn,'utf8');
    // 检测连接
    if (!$conn) {
        die("连接失败: " . mysqli_connect_error());
    }
    //echo "连接成功";
    if (isset($_GET["q"]))
        $q = $_GET['q'];
    else {
        $q = '';
    }
    // 每页显示数量
    $num_rec_per_page=9;
    //起始位置
    //echo isset($_GET["page"]);
    // $_GET["page"];
    if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
    $start_from = ($page-1) * $num_rec_per_page;
    
    //limit start,count
    $sql = "select * from film where title like '%$q%' limit $start_from, $num_rec_per_page";
    //echo $sql;
    $sql_num = "select count(*) 'sum' from film where title like '%$q%'";
    
    $res = mysqli_query($conn, $sql);
    
    $arr=array();//开始转换
    if(mysqli_num_rows($res) > 0){
        while ($re = mysqli_fetch_assoc($res)) {
            $arr[] = $re;
        }
        //echo json_encode($arr);
        $res_sum = mysqli_query($conn, $sql_num);
        
        $sum = mysqli_fetch_assoc($res_sum);
        //echo json_encode($sum);
        
        //总页数
        $page_count_sum = ceil($sum['sum']/$num_rec_per_page);
        //下一页
        if($page < $page_count_sum)
            $next= $page+1;
        else
            $next = $page;
        //上一页
        if($page > 1)
            $pre = $page-1;
        else
            $pre= 1;
        
        
        $data = array();
        $data['sum'] = $sum['sum'];
        $data['page_count_sum'] = $page_count_sum;
        $data['page']=$page;
        $data['next']=$next;
        $data['pre'] = $pre;
        $data['q']=$q;
        $data['list'] = $arr;
        echo json_encode($data);
        
    }
    else{
        echo "查无结果";
        
    } 
    mysqli_close($conn);
?>
