<?
header("HTTP/1.0 200 OK");
if(empty($_GET['page'])) $_GET['page']='main';
require_once('pages/'.$_GET['page'].'.php');
?>