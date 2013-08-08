<?
 //Ядро системы
class Elements{
    public static $db;
    public static $error=array();
    public static $debug;
    public static $app_id;
    public static $foreign_select='select';
    public static $root_path;
    public static $lang='ru';

    public static function init($app_id, $db, $root_path, $debug=false){
        self::$app_id    = $app_id;
        self::$db        = $db;
        self::$debug     = $debug;
        self::$root_path = $root_path;
    }

    public static function debug(){
        self::$debug=true;
    }

    public static function getById($element_id){
        if(!$type=self::getElementType($element_id)) return false;
        $params['type']=$type['name'];
        $params['filter']='id='.$element_id;
        $elements=self::get($params);
        return $elements[0];
    }

    public static function get($params=array()){
        if(empty($params['limit'])) $params['limit']=30;
        if(empty($params['page'])) $params['page']=0;

        if(!$type=self::getType($params['type'])) return false;
        if(!$types=self::getFullType($type['id'])) return false;

        $sql="SELECT e.*";
        foreach($types as $i=>$type) $sql.=", t$i.* ";
        $sql.="FROM `elements` AS e ";
        $table="et";
        foreach($types as $i=>$type) {
            $table.='_'.$type['name'];
            $sql.="LEFT JOIN `".$table."` AS t$i ON e.id=t$i.element_id ";
        }
        $sql.="WHERE e.app_id='".self::$app_id."' AND e.type_id='".$type['id']."' ";
        if(!empty($params['filter'])){
            $sql.="AND (";
            $sql.=self::filterToSql($params['filter']);
            $sql.=") ";
        }
        $sql.="LIMIT ".($params['page']*$params['limit']).",".($params['page']*$params['limit']+$params['limit'])." ";
        $elements=self::$db->q($sql,self::$debug,false);
        return $elements;
    }

    private static function filterToSql($filter){
        return $filter;
    }

    public static function set($params=array()){
        if(!empty($params['id'])){
            $prx="UPDATE ";
            $params['element_id']=$params['id'];
            unset($params['id']);
            $types=self::getElementFullType($params['element_id']);
        }
        else{
            $prx="INSERT ";
            if(!empty($params['type'])){
                $type=self::getType($params['type']);
                $types=self::getFullType($type['id']);
            }
            else{
                self::$error['code']=2;
                self::$error['desc']='Missing element type id';
                if(self::$debug) print_r(self::$error);
                return false;
            }
            self::$db->q("INSERT INTO `elements` SET `type_id`='".$params['type']."', `app_id`='".self::$app_id."'",self::$debug);
            $params['element_id']=self::$db->q("SELECT LAST_INSERT_ID()",self::$debug);
        }

        foreach($types as $i=>$type){
            $types[$i]['fields']=self::getTypeFields($type);
            //print_r($types[$i]['fields']);
            foreach($types[$i]['fields'] as $j=>$field){
                foreach($params as $k=>$val){
                    if($k==$field['Field']) $types[$i]['fields'][$j]['val']=$val;
                }
                if(!isset($types[$i]['fields'][$j]['val'])) unset($types[$i]['fields'][$j]);
            }
        }

        foreach($types as $type){
            $sql=$prx."`et_".$type['name']."` SET ";
            $i=0;
            foreach($type['fields'] as $field){
                if($i!=0) $sql.=", ";
                $sql.="`".$field['Field']."`=";
                if($field['val']=='NULL') $sql.="NULL";
                else $sql.="'".$field['val']."'";
                $i++;
            }
            if($prx=="UPDATE ") $sql.=" WHERE `element_id`='".$params['element_id']."'";
            return self::$db->q($sql,self::$debug);
        }
        if($prx=="INSERT ") return $params['element_id'];
        else return true;
    }

    public static function delete($element_id){
        $log=false;
        if(is_array($element_id)){
            $log=array();
            foreach ($element_id as $id){
                $log=self::delete($id);
            }
        }
        else{
            if(self::$db->q("DELETE FROM `elements` WHERE `id`='$element_id' AND `app_id`='".self::$app_id."'",self::$debug)) {
            $log=$element_id;
            }
            else $log=false;
        }
        return $log;
    }

    public static function getTypeById($type_id){
        $filter=array('id'=>$type_id);
        $types=self::getTypes($filter);
        if(!empty($types)) return $types[0];
        else {
            self::$error['code']=3;
            self::$error['desc']='Type id:'.$type_id.' not found';
            if(self::$debug) print_r(self::$error);
            return false;
        }
    }

    public static function getType($type_name_or_id){
        if(is_numeric($type_name_or_id)) $type=self::getTypeById($type_name_or_id);
        else $type=self::getTypeByName($type_name_or_id);
        return $type;
    }

    public static function getTypes($filter=array()){
        $sql="SELECT * FROM `types` ";

        if(!empty($filter)){
            $sql.="WHERE ";
            foreach($filter as $i=>$val) $sql.="`$i`='$val', ";
            $sql=substr($sql,0,strlen($sql)-2); //Mad skill to trim last ', ';
        }
        return self::$db->q($sql,self::$debug,false);
    }

    private static function getElementType($element_id){
        $element=self::getElementById($element_id);
        $types=self::getTypeById($element['type_id']);
        return $types;
    }

    private static function getElementFullType($element_id){
        $element=self::getElementById($element_id);
        $types=self::getFullType($element['type_id']);
        return $types;
    }

    private static function getElementById($element_id){
        if($element=self::$db->q("SELECT * FROM `elements` WHERE `id`='$element_id' AND `app_id`='".self::$app_id."'",self::$debug)) return $element;
        else{
            self::$error['code']='1';
            self::$error['desc']='Element id:'.$element_id.' not found';
            if(self::$debug) print_r(self::$error);
            return false;
        }
    }

    public static function getTypeByName($type_name){
        $filter=array('name'=>$type_name);
        $types=self::getTypes($filter);
        if(!empty($types)) return $types[0];
        else {
            self::$error['code']=4;
            self::$error['desc']='Type name:'.$type_name.' not found';
            if(self::$debug) print_r(self::$error);
            return false;
        }
    }

    public static function getTypeClass($type_name){
        $class['name']='Elements';
        $class['path']=self::$root_path.'/core/core.php';
        $path=self::$root_path."/core/classes/".$type_name.".php";
        if(file_exists($path)) {
            $class['name']=ucfirst($type_name);
            $class['path']=$path;
        }
        return $class;
    }

    public static function getFullType($type_id){
        $types=array();
        $type=self::getType($type_id);
        $types[]=$type;
        while(!empty($type['parent'])){
            $type=self::getTypeById($type['parent']);
            $types[]=$type;
        }
        return array_reverse($types);
    }

    public static function getTypeFields($type){
        $types=self::getFullType($type['id']);
        $allFields=array();
        $table='et';
        foreach($types as $j=>$type){
            $table=$table.'_'.$type['name'];
            $fields=self::$db->q('SHOW FULL COLUMNS FROM `'.$table.'`',self::$debug);
            foreach($fields as $i=>$field){
                if($field['Key']=='MUL') {
                    $sql="
                    SELECT k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME
                    FROM information_schema.TABLE_CONSTRAINTS i
                    LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
                    WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY'
                    AND k.COLUMN_NAME = '".$field['Field']."'
                    AND i.TABLE_SCHEMA = DATABASE()
                    AND i.TABLE_NAME = '".$table."'";
                    $inf=self::$db->q($sql,self::$debug);
                    if(!empty($inf)) $fields[$i]['FK']=substr($inf['REFERENCED_TABLE_NAME'],strripos($inf['REFERENCED_TABLE_NAME'],'_')+1);

                    /*echo "<pre>";
                    print_r($inf);
                    echo "</pre>"; */
                }
                if($j!=0&$field['Field']=='element_id') unset($fields[$i]);
            }
            $allFields=array_merge($allFields,$fields);
        }
        return $allFields;
    }

    public static function translate($text,$ucfirst=false,$lang='en'){
        if(empty($ucfirst)) $ucfirst=ctype_upper(substr($text,0,1));
        if($lang==self::$lang) {
            if($ucfirst) return mb_convert_case($text, MB_CASE_TITLE, "UTF-8");
            else return $text;
        }
        $sql="SELECT `".self::$lang."` FROM `lang` WHERE `".$lang."`='".$text."'";
        if($translate=self::$db->q($sql,self::$debug)) $text=$translate;
        if($ucfirst) $text=mb_convert_case($text, MB_CASE_TITLE, "UTF-8");
        return $text;
    }
}
?>