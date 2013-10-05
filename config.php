<?
$avx['sql_driver']   = 'mysql';
$avx['sql_host']     = 'localhost';
$avx['sql_database'] = 'elementus';
$avx['sql_user']     = 'elementus';
$avx['sql_pass']     = '123456';
$avx['prx']          = '';
$avx['sql_charset']  = 'utf8';

$prx=$avx['prx'];

define(TIME,time()+3600*6);
define(TIMESTAMP,date('Y-m-d H:i',TIME));
?>