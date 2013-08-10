<? require_once("core/init.php"); ?>
<? require_once("core/actions.php"); ?>
<?
if(empty($_GET['section'])) $_GET['section']='main';
if($data['section']=Sections::getByPath($_GET['section'])) header("HTTP/1.0 200 OK");
?>
<? require_once("templates/demo/header.php"); ?>
<? require_once("templates/demo/pages/".$data['section']['template'].".php"); ?>
<? require_once("templates/demo/footer.php"); ?>