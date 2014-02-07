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
    var $cols;
    var $row;
    var $cell;
    var $cells;
    var $tag;
    var $data;
    var $errors;

    function xml()
    {
        $this->parser = xml_parser_create();

        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, "tag_open", "tag_close");
        xml_set_character_data_handler($this->parser, "cdata");

        $this->rows=array();
        $this->rows=array();
        $this->cells=array();
        $this->tag=array();
        $this->data=false;
        $this->errors=array();
    }

    function parse($data, $is_final=false)
    {
        return xml_parse($this->parser, $data, $is_final);
    }

    function tag_open($parser, $name, $attr)
    {
        $this->tag=array('name'=>$name,'attr'=>$attr);
        if($name=='CELL') $this->cell=$this->tag;
    }

    function cdata($parser, $cdata)
    {
        $this->tag['value']=$cdata;
    }

    function tag_close($parser, $name)
    {
        global $type;
        if($name=='DATA') $this->data=$this->tag['value'];
        if($name=='CELL') {
            if(!empty($this->cell['attr']['SS:INDEX'])) $this->cells[(int)$this->cell['attr']['SS:INDEX']-1]=$this->data;
            else $this->cells[]=$this->data;
            //Add merged cells as empty cells
            if(!empty($this->cell['attr']['SS:MERGEACROSS'])){
                for($i=1; $i<=$this->cell['attr']['SS:MERGEACROSS']; $i++) $this->cells[]=false;
            }
            $this->data=false;
            $this->cell=array();
        }
        if($name=='ROW'){
            //Get cols names from first row
            if(empty($this->cols)) {
                $this->cols=$this->cells;
                foreach($this->cols as $col){
                    //if(!in_array($col,$db_table['fields'])) $this->errors[]='Column '.$col.' does not exist in database table';
                }
                if(!empty($this->errors)){
                    foreach($this->errors as $error) echo $error.'<br>';
                    //exit;
                }
            }
            else{
                $row=implode('',$this->cells);
                if(!empty($row)){
                    $element=array('type'=>$type['id']);
                    foreach($this->cols as $i=>$col){
                        if(!empty($col)){
                            $element[$col]=$this->cells[$i];
                        }
                    }
                    E::set($element);
                }
            }
            $this->cells=array();
        }
    }
}
?>