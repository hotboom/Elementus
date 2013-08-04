<?
//Simple router
$url=explode('/',$_SERVER['REQUEST_URI']);
$prev='';
foreach($url as $i=>$val){
    if(empty($val)|$val=='admin'|$val==$prev) continue;
    if(!empty($url[$i+1])) $_GET[$val]=$url[$i+1];
    else $_GET['id']=$val;
    $prev=$url[$i+1];
}
header("HTTP/1.0 200 OK");
require_once('pages/'.$_GET['page'].'.php');
?>