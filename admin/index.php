<?
require_once("../core/init.php");
header("HTTP/1.0 200 OK");

if(empty($_GET['page'])) $_GET['page']='main';
//Elements::debug();
if(!empty($_GET['exit'])) Users::logout();
if(Users::$user['group_id']!='19') $_GET['page']='login';
require_once('pages/'.$_GET['page'].'.php');
?>