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
E::init($db,$root_path);

//Template
require_once($root_path."/core/types/template.php");
Template::init();

//Sections
require_once($root_path."/core/types/sections.php");
S::init();

//Users
require_once($root_path."/core/types/users.php");
Users::init();

//Helper functions
require_once($root_path."/core/helper.php");

//Image functions
require_once($root_path."/core/image.func.php");

//Mail functions
require_once($root_path."/core/mail.func.php");
?>