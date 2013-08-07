<?
//Simple router
$url=explode('/',$_SERVER['REQUEST_URI']);
$prev='';
$_GET['section']=$url[1];
unset($url[1]);

foreach($url as $i=>$val){
    if(empty($val)|$val==$prev) continue;
    if(!empty($url[$i+1])) $_GET[$val]=$url[$i+1];
    else $_GET['id']=$val;
    $prev=$url[$i+1];
}
require_once('index.php');
?>