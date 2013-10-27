<?
if(empty($_SERVER['DOCUMENT_ROOT'])) $_SERVER['DOCUMENT_ROOT']='/var/www/gsm-opt/data/www/gsm-opt.ru';

include($_SERVER['DOCUMENT_ROOT']."/admin/config.php");
mysql_query("SET NAMES utf8");
include($_SERVER['DOCUMENT_ROOT']."/yml_import/functions.php");

$file=$_SERVER['DOCUMENT_ROOT']."/yml_import/import.zip";
//if(!file_exists($file)) die('Arhiv not found');

$sql="SELECT `date` FROM `avx-import` WHERE `type`='catalog' ORDER BY `id` DESC LIMIT 0,1";
$r=mysql_fetch_array(mysql_query($sql));
if(strtotime($r[0])>filectime($file)) die('Arhiv not updated');

$depth = 0;
$tags = array();
$item = '';
$field = '';
$cnum=0;
$pnum=0;
$pmnum = 0;
$dmnum = 0;
$category = array();
$product = array();
$paymethod = array();
$deliverymethod = array();

$xmlfile=unzip($file);
if (!($fp = fopen($xmlfile, "r"))) {
die("could not open XML input");
}

function startElement($parser, $name, $attrs){
global $depth, $tags;
global $item, $field, $category, $product, $paymethod, $deliverymethod;
switch ($name) {
case 'GROUP':
$category = array();
$item = $name;
break;
case 'OFFER':
$product = array();
$item = $name;
break;
case 'PAYMETHOD':
$paymethod = array();
$item = $name;
break;
case 'DELIVERYMETHOD':
$deliverymethod = array();
$item = $name;
break;
case 'STORE':
$store = array();
$item = $name;
break;
default:
$item =
$field = strtolower($name);
}
}

function endElement($parser, $name){
global $item, $field;
global $category, $product, $paymethod, $deliverymethod;
global $cnum, $pnum, $pmnum, $dmnum;

//	echo '</' . $name . '><br>';
switch ($name) {
case 'GROUP':
add_category2($category);
$cnum++;
break;
case 'OFFER':
if(!$product['deleted']) add_products2($product);
else delete_product($product);
$pnum++;
break;
case 'PAYMETHOD':
add_paymethod($paymethod);
$pmnum++;
break;
case 'DELIVERYMETHOD':
add_deliverymethod($deliverymethod);
$dmnum++;
break;
case 'STORE':
//            add_store($deliverymethod);
//            $dmnum++;
break;
default: // Неизвестный тэг

}
}

function stringElement($parser, $str){
global $item, $field;
global $cat_flag;
global $category, $product, $paymethod, $deliverymethod;
switch ($item) {
case 'GROUP':
$category[$field] .= $str;
break;
case 'OFFER':
$product[$field] .= $str;
break;
case 'PAYMETHOD':
$paymethod[$field] .= $str;
break;
case 'DELIVERYMETHOD':
$deliverymethod[$field] .= $str;
break;

}
}

$xml_parser = xml_parser_create();

xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "stringElement");

$log="\n";

while ($data = fgets($fp))
{
if (!xml_parse($xml_parser, $data, feof($fp)))
{
print "!";
$log.= "XML Error: ";
$log.= xml_error_string(xml_get_error_code($xml_parser));
$log.= " at line ".xml_get_current_line_number($xml_parser);
break;
}
}
$sql="INSERT INTO `avx-import` SET `type`='catalog', `date`='".date('Y-m-d H:i',time())."'";
mysql_query($sql) or die(mysql_error());
$log.= "Import completed \n";
$log.= "Payment methods: $pmnum \n";
$log.= "Delivery methods: $dmnum \n ";
$log.= "Categories: $cnum \n";
$log.= "Products: $pnum \n ";
echo $log;

$sql="SELECT * FROM `avx-contacts`";
$row=mysql_fetch_assoc(mysql_query($sql));
$em=explode(",",$row['email']);

foreach($em as $val){
mail("$val","gsm-opt.ru: импорт завершен", $log, "From: no-reply@gsm-opt.ru \nContent-Type: text/plain; charset=\"windows-1251\";");
}
?>