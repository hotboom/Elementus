<?
define('CMS_DIR', dirname(__FILE__) .'/');
define('TEMPLATES_DIR', CMS_DIR .'templates/');
define('COMPONENTS_DIR', CMS_DIR .'app/components/');
define('MODULES_DIR', CMS_DIR .'app/modules/');
define('SAPI_NAME', php_sapi_name());

$avx['sql_driver']   = 'mysql';
$avx['sql_host']     = 'localhost';
$avx['sql_database'] = 'baza59';
$avx['sql_user']     = 'baza59';
$avx['sql_pass']     = 'hnguzMpc';
$avx['prx']          = '';
$avx['sql_charset']  = 'utf8';

$prx=$avx['prx'];

define(TIME,time()+3600*6);
define(TIMESTAMP,date('Y-m-d H:i',TIME));
?>