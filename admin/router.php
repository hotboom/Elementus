<?
//Simple router
$url=parse_url($_SERVER['REQUEST_URI']);
$url['path']=explode('/',$url['path']);
$url['path'] = array_slice($url['path'], 2);
$_GET['page']=$url['path'][0];
$url['path'] = array_slice($url['path'], 1);
$query='';
for($i=0; $i<count($url['path']); $i=$i+2){
    $query.=$url['path'][$i].'='.$url['path'][$i+1].'&';
}
parse_str($query,$arr);
$_GET=array_merge($_GET,$arr);
if(!empty($_GET['page'])) require_once("index.php");
?>