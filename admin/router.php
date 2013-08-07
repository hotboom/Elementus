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

require_once("index.php");
?>