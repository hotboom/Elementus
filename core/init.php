<?
//Config
$root_path=str_replace('core','',__DIR__);
require_once($root_path."/config.php");

//Mysql class
require_once($root_path."/core/db/class.mysql.php");
$db  = new MySQL();
if(!$db->getConnect(false, $avx['sql_host'], $avx['sql_user'], $avx['sql_pass'], $avx['sql_database'], $avx['sql_charset'])) fatal('Не удалось установить соединение с БД');

//Core
require_once($root_path."/core/core.php");
Elements::init(1,$db,$root_path);

//Template
require_once($root_path."/core/classes/template.php");
Template::init(1);

//Sections
require_once($root_path."/core/classes/sections.php");

/* Shortcut functions */
//Translate
function t($text,$lang='en'){
    return Elements::translate($text,$lang);
}
?>