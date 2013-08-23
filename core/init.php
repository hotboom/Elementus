<?
//Config
$root_path=str_replace('core','',dirname(__FILE__));
require_once($root_path."/config.php");

//Mysql class
require_once($root_path."/core/db/class.mysql.php");
$db  = new MySQL();
if(!$db->getConnect(false, $avx['sql_host'], $avx['sql_user'], $avx['sql_pass'], $avx['sql_database'], $avx['sql_charset'])) die('Не удалось установить соединение с БД');

//Core
require_once($root_path."/core/core.php");
Elements::init($db,$root_path);

//Template
require_once($root_path."/core/types/template.php");
Template::init();

//Sections
require_once($root_path."/core/types/sections.php");

//Users
require_once($root_path."/core/types/users.php");
Users::init();

/* Shortcut functions */
//Translate
function t($text,$ucfirst='auto',$lang='en'){
    return Elements::translate($text,$ucfirst,$lang);
}
?>