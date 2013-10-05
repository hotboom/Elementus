<?
require_once("../core/init.php");

$pages=array(
    'main',
    'dashboard',
    'element',
    'field_form',
    'files',
    'login',
    'app_form',
    'type',
    'type_fields',
    'type_form',
    'types'
);

if(!empty($_GET['exit'])) Users::logout();
if(empty($_GET['page'])) $_GET['page']='main';
if(!in_array($_GET['page'],$pages)) $_GET['page']='404';
else {
    if(Users::$user['group_id']!='19'&&$_GET['page']!='login') {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: login");
        exit();
    }
    header("HTTP/1.0 200 OK");
}
require_once('pages/'.$_GET['page'].'.php');
?>