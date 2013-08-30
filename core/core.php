<?
 //Ядро системы
class E{
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
        $filter="domain='".$_SERVER['HTTP_HOST']."'";
        $apps=self::getApps($params=array('filter'=>$filter));
        if(empty($apps[0])) $apps=self::getApps($params=array('order'=>'id','limit'=>'1'));
        self::$app=$apps[0];

        if(!empty(self::$app['template_id'])) self::$template=self::getById(self::$app['template_id']);
        else {
            $templates=self::get(array('type'=>'templates','order'=>'id','limit'=>'1'));
            self::$template=$templates[0];
        }
    }

    public static function debug($debug=true){
        self::$debug=$debug;
    }

    public static function getApps($params=array()){
        if(empty($params)) return self::$app;
        $sql="SELECT id,name,domain,template_id FROM apps";
        if(!empty($params['filter'])) $sql.=' WHERE '.$params['filter'];
        if(!empty($params['order']))  $sql.=' ORDER BY '.$params['order'];
        if(empty($params['page'])) $params['page']=0;
        if(!empty($params['limit']))  $sql.=' LIMIT '.$params['page']*$params['limit'].','.$params['limit'];
        $apps=self::$db->q($sql,self::$debug,0);
        return $apps;
    }

    public static function setApp($params){
        if(empty($params['id'])) $insert=true;
        else $insert=false;
        $sql=($insert ? 'INSERT '.'INTO' : 'UPDATE')." apps SET
        name='".$params['name']."',
        domain='".$params['domain']."',
        template_id=".(empty($params['template_id']) ? "NULL" : "'".$params['template_id']."'")."
        WHERE id='".$params['id']."'";

        return self::$db->q($sql,self::$debug);
    }

    public static function get($params=array()){
        if(empty($params['limit'])) $params['limit']=30;
        if(empty($params['page'])) $params['page']=0;

        if(!$type=self::getType($params['type'])) return false;
        if(!$types=self::getFullType($type['id'])) return false;
        foreach($types as $i=>$t){
            $types[$i]['table']=self::getTypeTableName($t);
            if(!self::$db->q("SHOW TABLES LIKE '".$types[$i]['table']."'",self::$debug)) unset($types[$i]);
        }
        $sql="SELECT e.*";
        foreach($types as $i=>$t) {
            $sql.=", t$i.* ";
        }
        $sql.="FROM `elements` AS e ";
        foreach($types as $i=>$t) {
            $sql.="LEFT JOIN `".$types[$i]['table']."` AS t$i ON e.id=t$i.element_id ";
        }
        $sql.="WHERE e.app_id='".self::$app['id']."' AND e.type_id='".$type['id']."' ";
        if(!empty($params['filter'])){
            $sql.="AND (";
            $sql.=$params['filter'];
            $sql.=") ";
        }
        $sql.="LIMIT ".($params['page']*$params['limit']).",".($params['page']*$params['limit']+$params['limit'])." ";
        $elements=self::$db->q($sql,self::$debug,false);
        return $elements;
    }

    public static function getById($element_id){
        if(!$type=self::getElementType($element_id)) return false;
        $params['type']=$type['name'];
        $params['filter']='id='.$element_id;
        $elements=self::get($params);
        return $elements[0];
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
            $types[$i]['table']=self::getTypeTableName($type);
            foreach($types[$i]['fields'] as $j=>$field){
                foreach($params as $k=>$val){
                    if($k==$field['Field']) $types[$i]['fields'][$j]['val']=$val;
                }
                if(!isset($types[$i]['fields'][$j]['val'])) unset($types[$i]['fields'][$j]);
            }
        }

        foreach($types as $type){
            $sql=$prx.$type['table']." SET ";
            $sql.="element_id='".$params['element_id']."'";
            foreach($type['fields'] as $field){
                $sql.=", ".$field['Field']."=";
                if($field['val']=='NULL') $sql.="NULL";
                else $sql.="'".$field['val']."'";
            }
            if($prx=="UPDATE ") $sql.=" WHERE `element_id`='".$params['element_id']."'";
            return self::$db->q($sql,self::$debug);
        }
        if($prx=="INSERT ") return $params['element_id'];
        else return true;
    }

    public static function delete($element_id){
        if(is_array($element_id)){
            $log=array();
            foreach ($element_id as $id){
                $log=self::delete($id);
            }
        }
        else{
            if(self::$db->q("DELETE FROM `elements` WHERE `id`='$element_id' AND `app_id`='".self::$app['id']."'",self::$debug)) $log=$element_id;
            else $log=false;
        }
        return $log;
    }

    public static function setType($params){
        if(empty($params['id'])) $insert=true;
        else $insert=false;
        $sql=($insert ? 'INSERT '.'INTO' : 'UPDATE')." types SET parent=".(empty($params['parent']) ? "NULL" : "'".$params['parent']."'").", name='".$params['name']."'";
        $sql.=" WHERE id='".$params['id']."'";

        return self::$db->q($sql,self::$debug);
    }

    public static function setTypeField($type,$params){
        $type=self::getType($type);
        $table=self::getTypeTableName($type);
        if(!self::$db->q("SHOW TABLES LIKE '".$table."'")){
            $sql="CREATE TABLE  ".$table." (
            element_id INT( 11 ) NOT NULL ,
            PRIMARY KEY (  element_id )
            ) ENGINE = INNODB DEFAULT CHARSET = utf8";
            self::$db->q($sql,self::$debug);
        }
        //Example: ALTER TABLE et_content_products CHANGE store store INT( 11 ) NOT NULL
        return self::$db->q("ALTER TABLE  ".$table." ".(empty($params['field']) ? 'ADD' :'CHANGE '.$params['field'])." ".$params['name']." ".$params['type']." NOT NULL",self::$debug); // AFTER  `field`
    }

    public static function deleteTypeField($type,$field_name){
        $type=self::getType($type);
        if(is_array($field_name)){
            $return=array();
            foreach($field_name as $name){
                $return[]=self::deleteTypeField($type,$name);
            }
            return $return;
        }
        $table=self::getTypeTableName($type);
        return self::$db->q("ALTER TABLE ".$table." DROP ".$field_name,self::$debug);
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
        if(is_array($type_name_or_id)) return $type_name_or_id;
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
        $class['name']='E';
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
        $type=self::getType($type);
        $types[]=$type;
        while(!empty($type['parent'])){
            $type=self::getTypeById($type['parent']);
            $types[]=$type;
        }
        return array_reverse($types);
    }

    public static function getTypeTableName($type){
        $type=self::getType($type);
        $table=$type['name'];

        while(!empty($type['parent'])){
            $type=self::getType($type['parent']);
            $table=$type['name'].'_'.$table;
        }
        return 'et_'.$table;
    }

    public static function getTypeFields($type){
        $type=self::getType($type);
        $table=self::getTypeTableName($type);
        if(!self::$db->q("SHOW TABLES LIKE '".$table."'")) return array();
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
            if($field['Field']=='element_id') unset($fields[$i]);
        }
        return $fields;
    }

    public static function getFullTypeFields($type){
        $types=self::getFullType($type);
        $allFields=array();
        foreach($types as $type){
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
        if($translate=self::$db->q($sql)) {
            $text=($ucfirst ? mb_convert_case($translate, MB_CASE_TITLE, "UTF-8") : mb_convert_case(substr($translate,0,1), MB_CASE_LOWER, "UTF-8").substr($translate,1));
        }
        else{ //Try translate by word
            if(strpos($text,' ')) {
                $words=explode(' ',$text);
                $text='';
                $text=self::translate($words[0],$ucfirst).' '; //Use $ucfirst to first word
                unset($words[0]);
                foreach($words as $word) $text.=self::translate($word).' ';
            }
        }
        return trim($text);
    }
}
?>