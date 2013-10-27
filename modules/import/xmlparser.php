<?
/*if(empty($_SERVER['DOCUMENT_ROOT'])) $_SERVER['DOCUMENT_ROOT']='/var/www/gsm-opt/data/www/gsm-opt.ru';

include($_SERVER['DOCUMENT_ROOT']."/admin/config.php");
mysql_query("SET NAMES utf8");
include($_SERVER['DOCUMENT_ROOT']."/yml_import/functions.php");

$file=$_SERVER['DOCUMENT_ROOT']."/yml_import/import.zip";
//if(!file_exists($file)) die('Arhiv not found');

$sql="SELECT `date` FROM `avx-import` WHERE `type`='catalog' ORDER BY `id` DESC LIMIT 0,1";
$r=mysql_fetch_array(mysql_query($sql));
if(strtotime($r[0])>filectime($file)) die('Arhiv not updated');
*/

class xml  {
    var $parser;
    var $tags;

    function xml()
    {
        $this->parser = xml_parser_create();

        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, "tag_open", "tag_close");
        xml_set_character_data_handler($this->parser, "cdata");

        $this->tags=array();
    }

    function parse($data, $is_final=false)
    {
        return xml_parse($this->parser, $data, $is_final);
    }

    function tag_open($parser, $tag, $attributes)
    {
        if(!in_array($tag,$this->tags)) $this->tags[]=$tag;
        echo "open:<br>";
        var_dump($tag, $attributes);
    }

    function cdata($parser, $cdata)
    {
        echo 'cdata:<br>';
        var_dump($cdata);
    }

    function tag_close($parser, $tag)
    {
        echo 'close:<br>';
        var_dump($tag);
    }

} // окончание определения класса xml

$xml_parser = new xml();

$xmlfile=$root_path.'upload/f56d2af56e09d54f78c2.xml';
if (!($fp = fopen($xmlfile, "r"))) die("could not open XML input");

while ($data = fgets($fp))
{
    if (!$xml_parser->parse($data,feof($fp))) break;
}

print_r($xml_parser->tags);
?>