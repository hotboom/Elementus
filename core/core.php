<?
 //Ядро системы
class Elements{
    public static $db;
    public static $error=array();
    public static $debug;
    public static $app;
    public static $foreign_select='select';
    public static $root_path;
    public static $lang='ru';
    public static $template;

    public static function init($db, $root_path, $debug=false){
        self::$db        = $db;
        self::$debug     = $debug;
        self::$root_path = $root_path;

        if(!self::$app=self::$db->q("SELECT id,name,domain,template_id FROM apps WHERE domain='".$_SERVER['HTTP_HOST']."'",self::$debug)) self::$app=self::$db->q("SELECT id,name,domain,template FROM apps ORDER BY id LIMIT 0,1",self::$debug);
        if(!empty(self::$app['template_id'])) self::$template=self::getById(self::$app['template_id']);
        else self::$template=self::$db->q("SELECT element_id AS id,name,path FROM et_templates ORDER BY id LIMIT 0,1",self::$debug);
    }

    public static function debug($debug=true){
        self::$debug=$debug;
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
        $sql.="WHERE e.app_id='".self::$app['id']."' AND e.type_id='".$type['id']."' ";
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
            self::$db->q("INSERT INTO `elements` SET `type_id`='".$params['type']."', `app_id`='".self::$app['id']."'",self::$debug);
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
            if(self::$db->q("DELETE FROM `elements` WHERE `id`='$element_id' AND `app_id`='".self::$app['id']."'",self::$debug)) {
            $log=$element_id;
            }
            else $log=false;
        }
        return $log;
    }

    public static function getTypeById($type_id){
        $types=self::getTypes("id='".$type_id."'");
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

    public static function getTypes($filter=false){
        $sql="SELECT * FROM `types` ";

        if(!empty($filter)){
            $sql.="WHERE ".$filter;
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
        if($element=self::$db->q("SELECT * FROM `elements` WHERE `id`='$element_id' AND `app_id`='".self::$app['id']."'",self::$debug)) return $element;
        else{
            self::$error['code']='1';
            self::$error['desc']='Element id:'.$element_id.' not found';
            if(self::$debug) print_r(self::$error);
            return false;
        }
    }

    public static function getTypeByName($type_name){
        $types=self::getTypes("name='".$type_name."'");
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

    public static function getFullType($type){
        $types=array();
        if(!is_array($type)) $type=self::getType($type);
        $types[]=$type;
        while(!empty($type['parent'])){
            $type=self::getTypeById($type['parent']);
            $types[]=$type;
        }
        return array_reverse($types);
    }

    public static function getTypeFields($type){
        $table='et';
        if(!is_array($type)) $type=self::getType($type);
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
                else $fields[$i]['FK']=false;
                /*echo "<pre>";
                print_r($inf);
                echo "</pre>"; */
            }
            if($j!=0&$field['Field']=='element_id') unset($fields[$i]);
        }
        return $fields;
    }

    public static function getFullTypeFields($type){
        $types=self::getFullType($type);
        $allFields=array();
        foreach($types as $j=>$type){
            $fields=self::getTypeFields($type);
            $allFields=array_merge($allFields,$fields);
        }
        return $allFields;
    }

    public static function translate($text,$ucfirst='auto',$lang='en'){
        $text=trim($text);
        if($ucfirst==='auto') $ucfirst=ctype_upper(substr($text,0,1));
        if($lang==self::$lang) { //Do not translate
            if($ucfirst) return mb_convert_case($text, MB_CASE_TITLE, "UTF-8");
            else return mb_convert_case(substr($text,0,1), MB_CASE_LOWER, "UTF-8").substr($text,1);
        }
        $sql="SELECT `".self::$lang."` FROM `lang` WHERE `".$lang."`='".$text."'";
        if($translate=self::$db->q($sql,self::$debug)) {
            $text=($ucfirst ? mb_convert_case($translate, MB_CASE_TITLE, "UTF-8") : mb_convert_case(substr($translate,0,1), MB_CASE_LOWER, "UTF-8").substr($translate,1));
        }
        else{ //Try translate by word
            if(strpos($text,' ')) {
                $words=explode(' ',$text);
                $text='';
                foreach($words as $word) $text.=self::translate($word).' ';
            }
        }
        return trim($text);
    }
}
?>