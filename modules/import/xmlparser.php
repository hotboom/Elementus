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
    var $temp;
    var $depth;

    function xml()
    {
        $this->parser = xml_parser_create();

        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, "tag_open", "tag_close");
        xml_set_character_data_handler($this->parser, "cdata");

        $this->tags=array();
        $this->temp=array();
        $this->depth=0;
    }

    function parse($data, $is_final=false)
    {
        return xml_parse($this->parser, $data, $is_final);
    }

    function tag_open($parser, $tag, $attributes)
    {

        //if(!in_array($tag,$this->tags)) $this->tags[]=$tag;
        $this->temp[count($this->temp)]=array('name'=>$tag,'depth'=>$this->depth);
        if(!empty($attributes)) $this->temp[count($this->temp)-1]['attr']=$attributes;
        $this->depth++;
    }

    function cdata($parser, $cdata)
    {
        $cdata=trim($cdata);
        if(!empty($cdata)) $this->temp[count($this->temp)-1]['value']=$cdata;
    }

    function tag_close($parser, $tag)
    {
        $this->tags=array_merge($this->tags,$this->temp);
        $this->temp=array();
        $this->depth=$this->depth-1;
    }

} // окончание определения класса xml

?>