<!DOCTYPE html> 
<html> 
<body> 
<?php 
echo "Hello World!";
echo "<hr>";
?> 

<?php 
    $x = 5;
    $y = 6;
    $z = $x + $y;
    echo $z;
    echo "<hr>";
?>

<?php
$txt="Hello world!";
$x=5;
$y=10.5;
echo "<hr>";
?>


<?php 
$x=5; // 全局变量 

function myTest() 
{ 
    //$GLOBALS;
//     global $x;
    $y=10; // 局部变量 
    echo "<p>测试函数内变量:<p>"; 
    echo "变量 x 为:"+ $GLOBALS['x']; 
    //echo $GLOBALS['x'];
    echo "<br>"; 
    echo "变量 y 为: $y"; 
}  

myTest(); 

echo "<p>测试函数外变量:<p>"; 
echo "变量 x 为: $x"; 
echo "<br>"; 
echo "变量 y 为: $y"; 
echo "<hr>";
?>

<?php
$txt1="学习 PHP";
$txt2="RUNOOB.COM";
$cars=array("Volvo","BMW","Toyota");
 
echo $txt1;
echo "<br>";
echo "在 $txt2 学习 PHP ";
echo "<br>";
echo "我车的品牌是 {$cars[0]}";
echo "<hr>";
?>

<?php 
    echo <<<JSON
    '<h1>我的第一个标题</h1>
    <p>我的第一个段落。</p>
    //777'
JSON;
    echo '<hr>';
?>

<?php 
$x = "Hello world!";
echo $x;
echo "<br>"; 
$x = "'Hello world!'";
echo $x;
echo '<hr>';
?>

<?php
// 区分大小写的常量名
define("GREETING", "欢迎访问 Runoob.com", true);
echo GREETING;    // 输出 "欢迎访问 Runoob.com"
echo '<br>';
echo greeting;   // 输出 "greeting"
echo  '<hr>'
?>


<?php 
$txt1="Hello world!"; 
$txt2="What a nice day!"; 
echo $txt1 . " " . $txt2; 
//echo  ""+$txt1 + $txt2;
echo "<br>";
echo $txt1. "" .$txt2;
echo strlen("aaa ");
echo strpos("aba","b");
echo strlen("中文字符");   // 输出 12
echo mb_strlen("中文字符",'utf-8');  // 输出 4
echo  '<hr>'
?>

<?php
$x=100; 
$y="100";
echo $x==$y;
echo var_dump($x == $y);
var_dump($x == $y);
echo "<br>";
var_dump($x === $y);
echo "<br>";
var_dump($x != $y);
echo "<br>";
var_dump($x !== $y);
echo "<br>";
 
$a=50;
$b=90;
 
var_dump($a > $b);
echo "<br>";
var_dump($a < $b);
echo "<hr>"
?>

<?php
$x = array("a" => "red", "b" => "green"); 
$y = array("c" => "blue", "d" => "yellow"); 
$z = $x + $y; // $x 和 $y 数组合并
$a =array("b" => "green","a" => "red"); 
var_dump($z);
var_dump($x == $a);
var_dump($x === $y);
var_dump($x != $y);
var_dump($x <> $y);
var_dump($x !== $y);
echo "<hr>"
?>

<?php
//$test = '菜鸟教';
$a = false;
// 普通写法
//$username = isset($test) ? $test : 'nobody';
//echo $username, PHP_EOL;
 
// PHP 5.3+ 版本写法
$username = $a ?"aaa": 'nobody';
echo $username, PHP_EOL;
echo "<hr>"
?>

<?php
$cars=array("Volvo","BMW","Toyota");
echo "I like " . $cars[0] . ", " . $cars[1] . " and " . $cars[2] . ".";
echo count($cars);
echo "<hr>"
?>

<?php 
echo $_SERVER['PHP_SELF'];
echo "<br>";
echo $_SERVER['SERVER_NAME'];
echo "<br>";
echo $_SERVER['HTTP_HOST'];
echo "<br>";
echo $_SERVER['SERVER_SOFTWARE'];
echo "<br>";
echo $_SERVER['HTTP_USER_AGENT'];
echo "<br>";
echo $_SERVER['SCRIPT_NAME'];
echo "<hr>"
?>


<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
Name: <input type="text" name="fname">
<input type="submit">
</form>

<?php 
if(array_key_exists("fname",$_REQUEST)){
	$name = $_REQUEST['fname']; 
	echo "传过来的值是：".$name; 
}
echo "<hr>"
?>

<?php
echo '这是第 " '  . __LINE__ . ' " 行';
?>
<?php
echo '该文件位于 " '  . __FILE__ . ' " ';
?>
<?php
class test {
    function _print() {
        echo '类名为：'  . __CLASS__ . "<br>";
        echo  '函数名为：' . __FUNCTION__ ;
    }
}
$t = new test();
$t->_print();
?>

<form action="/b.php" method="post">
		名字: <input type="text" name="fname">
		年龄: <input type="text" name="age">
		<input type="submit" value="提交">
	</form>

</body> 
</html>