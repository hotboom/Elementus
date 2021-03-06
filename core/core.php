<?
/**
 * Elementus core class
 *
 * This is the long description for a DocBlock. This text may contain
 * multiple lines and even some _markdown_.
 *
 * * Markdown style lists function too
 * * Just try this out once
 *
 * The section after the long description contains the tags; which provide
 * structured meta-data concerning the given element.
 *
 * @author  Andrey Nedorostkov <huntedbox@gmail.com>
 *
 * @since 1.1
 */
class E{
    /** @type object Database object */
    public static $db;
    /** @type array Current error */
    public static $errors=array();
    /** @type bool Debug mode on/off */
    public static $debug;
    /** @type array Current app params array */
    public static $app;
    /** @type string Path to Elementus root directory */
    public static $root_path;
    /** @type string Interface language */
    public static $lang;
    /** @type string Template name */
    public static $template;
    /** @type mixed Temp variable for recursion functions results */
    private static $recursion_temp=false;

    private static $buffer=array();
    private static $buffer_functions=array();

    /**
     * Initiate core
     *
     * @param object $db Database object
     * @param string $root_path Database object
     * @param bool $debug Show debug
     *
     * @return void
     */
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

        self::$lang='ru';
        if(!empty($_COOKIE['lang'])){
            $sql="SHOW COLUMNS FROM `lang`";
            $cols=self::$db->q($sql);
            foreach($cols as $i=>$col){
                if($i<2) continue;
                if($col['Field']===$_COOKIE['lang']) self::$lang=$col['Field'];
            }
        }

        //Memoizated functions
        ob_start(array('E',"end_buffer"));
    }

    /**
     * Show debug info
     *
     * @param bool|string $debug Debug level: true - show database queries, 'explain' - show database database queries and explain info
     *
     * @return void
     */
    static function debug($debug=true){
        self::$debug=$debug;
    }

    /**
     * Add core error and return false
     *
     * @param int $code Error number
     * @param string $desr Error description
     *
     * @return bool
     */
    static function error($code,$desr){
        self::$errors[]=array('code'=>$code,'desc'=>$desr);
        return false;
    }

    /**
     * Return applications array
     *
     * @param array $params Params to filter, sort and limit applications list
     *
     * @return array
     */
    public static function getApps($params=array()){
        if(empty($params)) return self::$app;
        $sql="SELECT id,name,domain,alias,template_id FROM apps";
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
        alias='".$params['alias']."',
        template_id=".(empty($params['template_id']) ? "NULL" : "'".$params['template_id']."'")."
        WHERE id='".$params['id']."'";

        return self::$db->q($sql,self::$debug);
    }

    public static function get($params=array()){
        if(!is_array($params)) {
            if(is_numeric($params)) {
                $params=array('filter'=>array('id'=>$params));
                $params['type']=self::getElementType($params['filter']['id']);
            }
            else $params=array('type'=>$params);
        }
        if(empty($params['page'])) $params['page']=0;

        if(!$type=self::getType($params['type'])) return false;
        if(!$types=self::getFullType($type['id'])) return false;

        $fields=array();
        foreach($types as $i=>$t){
            $types[$i]['table']=self::getTypeTableName($t);
            if(!self::$db->q("SHOW TABLES LIKE '".$types[$i]['table']."'",self::$debug)) {
                unset($types[$i]);
                continue;
            }
            $types[$i]['fields']=self::getTypeFields($t);
            $fields=array_merge($fields, $types[$i]['fields']);
        }
        if(empty($types)) return array();

        if(!empty($params['count']))
        {
            $params['limit']=false;
            $sql="SELECT count(*)";
        }
        else{
            $sql="SELECT e.*";
            foreach($types as $i=>$t) {
                $sql.=", t$i.* ";
            }
        }
        $sql.="FROM `elements` AS e ";
        foreach($types as $i=>$t) {
            $sql.="LEFT JOIN `".$types[$i]['table']."` AS t$i ON e.id=t$i.element_id ";
        }
        $sql.="WHERE e.app_id='".self::$app['id']."' ";
        if($params['subtypes']) {
            $sub_types=self::getSubTypesList($type);
            $sub_types[]=$type;
            $sql.="AND (";
            foreach($sub_types as $i=>$sub_type) $sql.=($i!=0 ? "OR " : "")."e.type_id='".$sub_type['id']."' ";
            $sql.=") ";
        }
        else $sql.="AND e.type_id='".$type['id']."' ";
        if(!empty($params['filter'])){
            if(is_array($params['filter'])){
                $filter='';
                $j=0;
                foreach($params['filter'] as $i=>$v) {
                    if($j>0) $filter.=' AND ';
                    if($v===false) $filter.="`".$i."` is NULL";
                    else $filter.="`".$i."`='".$v."'";
                    $j++;
                }
                $params['filter']=$filter;
            }
            $sql.="AND (";
            $sql.=$params['filter'];
            $sql.=") ";
        }
        if(!empty($params['group'])) $sql.="GROUP BY `".$params['group']."`";
        if(!empty($params['order'])) {
            if(is_array($params['order'])) $sql.="ORDER BY `".$params['order'][0]."` ".($params['order'][1] ? 'DESC' : 'ASC')." ";
            else $sql.="ORDER BY `".$params['order']."` ";
        }
        if(isset($params['limit'])){
            if($params['limit']) $sql.="LIMIT ".($params['page']*$params['limit']).",".($params['page']*$params['limit']+$params['limit'])." ";
        }
        $elements=self::$db->q($sql,self::$debug,false);
        if(!empty($params['count'])) return $elements[0]['count(*)'];

        //Fill multiple fields
        foreach($types as $i=>$type){
            foreach($type['fields'] as $field){
                if($field['multiple']){
                    foreach($elements as $j=>$element){
                        $sql="SELECT ".$field['name']." FROM `multi_".$type['name']."__".$field['name']."` WHERE element_id='".$element['id']."'";
                        $rows=self::$db->q($sql,self::$debug,false);
                        $elements[$j][$field['name']]=array();
                        foreach($rows as $row) $elements[$j][$field['name']][]=$row[$field['name']];
                    }
                }
            }
        }

        return $elements;
    }

    public static function getById($element_id){
        $elements=self::get($element_id);
        return $elements[0];
    }

    static function getSelectList($params){
        if(!$elements=self::get($params)) return false;
        foreach($elements as $i=>$element){
            unset($element['id']);
            unset($element['type_id']);
            unset($element['app_id']);
            unset($element['element_id']);
            $elements[$i]['option_name']=implode(' ',$element);
        }
        return $elements;
    }

    public static function set($params=array()){
        //Batch set
        if(is_array($params['id'])){
            if(count($params['id'])>1){
                foreach($params['id'] as $id){
                    $params['id']=$id;
                    //Ignore empty fields
                    foreach($params['fields'] as $i=>$field) {
                        if(empty($field)) unset($params['fields'][$i]);
                    }
                    $ret[]=self::set($params);
                    return $ret;
                }
            }
            else $params['id']=$params['id'][0];
        }

        if(!empty($params['type'])){
            $type=self::getType($params['type']);
            $types=self::getFullType($type);
        }
        if(!empty($params['id'])){
            $prx="UPDATE ";
            $params['element_id']=$params['id'];
            unset($params['id']);
            if(empty($types)) $types=self::getElementFullType($params['element_id']);
        }
        else{
            $prx="INSERT ";
            if(empty($params['type'])){
                self::error(2,'Missing element type name or id');
                if(self::$debug) print_r(self::$errors);
                return false;
            }
            self::$db->q("INSERT INTO `elements` SET `type_id`='".$type['id']."', `app_id`='".self::$app['id']."'",self::$debug);
            $params['element_id']=self::$db->q("SELECT LAST_INSERT_ID()",self::$debug);
        }

        foreach($types as $i=>$type){
            $types[$i]['fields']=self::getTypeFields($type);
            $types[$i]['table']=self::getTypeTableName($type);
            foreach($types[$i]['fields'] as $j=>$field){
                foreach($params as $k=>$val){
                    if($k==$field['name']) $types[$i]['fields'][$j]['val']=$val;
                }
                if(!isset($types[$i]['fields'][$j]['val'])) unset($types[$i]['fields'][$j]);
            }
        }

        foreach($types as $type){
            $sql=$prx.$type['table']." SET ";
            $sql.="element_id='".$params['element_id']."'";
            foreach($type['fields'] as $field){
                if($field['multiple']){
                    if(!is_array($field['val'])) return self::error('21','Value of multiple field "'.$field['name'].'" must be an array, '.gettype($field['val']).' given');
                    $tbl='multi_'.$type['name'].'__'.$field['name'];
                    if(!self::$db->q("TRUNCATE TABLE  `$tbl`",self::$debug)) return false;
                    foreach($field['val'] as $v){
                        if(is_array($v)&&$field['type']=='elements'){
                            $v=self::set(array_merge(array('type'=>$field['elements_type']),$v));
                        }
                        if(empty($v)&&$field['nullable']) $v="NULL";
                        else $v="'".$v."'";
                        if(!self::$db->q("INSERT INTO `$tbl` SET element_id='".$params['element_id']."', ".$field['name']."=".$v,self::$debug)) return false;
                    }
                }
                else{
                    if(is_array($field['val'])&&$field['type']=='elements'){
                        $field['val']=self::set(array_merge(array('type'=>$field['elements_type']),$field['val']));
                    }
                    $sql.=", `".$field['name']."`=";
                    if(empty($field['val'])&&$field['nullable']) $sql.="NULL";
                    else $sql.="'".$field['val']."'";
                }
            }
            if($prx=="UPDATE ") $sql.=" WHERE `element_id`='".$params['element_id']."'";
            if(!self::$db->q($sql,self::$debug)) return false;
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
            $types=self::getElementFullType($element_id);
            foreach($types as $type){
                $type['table']=self::getTypeTableName($type);
                if(!self::$db->q("DELETE FROM `".$type['table']."` WHERE `element_id`='$element_id'",self::$debug)) return false;
            }
            if(!self::$db->q("DELETE FROM `elements` WHERE `id`='$element_id' AND `app_id`='".self::$app['id']."'",self::$debug)) return false;
        }
        return true;
    }

    static function replace($type, $field, $find, $replace){
        //update ИМЯ_ТАБЛИЦЫ set ИМЯ_ПОЛЯ = replace(ИМЯ_ПОЛЯ, 'что ищем', 'на что заменяем') where ИМЯ_ПОЛЯ like 'что ищем%';
        if(!$type=self::getType($type)) return false;
        if(!$types=self::getFullType($type['id'])) return false;

        foreach($types as $i=>$t){
            $types[$i]['table']=self::getTypeTableName($t);
            if(!self::$db->q("SHOW TABLES LIKE '".$types[$i]['table']."'",self::$debug)) continue;
            $fields=self::getTypeFields($t);
            foreach($fields as $f){
                if($f['name']==$field){
                    if(!self::$db->q("UPDATE ".$types[$i]['table']." set ".$f['name']." = replace(".$f['name'].", '".$find."', '".$replace."')",self::$debug)) return false;
                }
            }
        }
    }

    static function count($params){
        $params['count']=true;
        if(!$count=self::get($params)) return false;
        return $count;
    }

    static function setType($params){
        $type=$params;
        if(empty($params['name'])) self::error(6,'Type name is empty');
        if(preg_match("/[^(\w)|(\-)]/",$params['name']))  self::error(7,'Type name may contain only latin letters and _ or - symbols');
        if(!empty($params['view'])&&!is_array($params['view'])) self::error(8,'Incorrect view format');
        if(!empty(self::$errors)) return false;
        $params['name']=mb_strtolower($params['name']);
        if(!empty($params['id'])) {
            $type=self::getType($params['id']);
            if($params['name']!=$type['name']) {
                if(!self::$db->q("RENAME TABLE  `".self::getTypeTableName($type)."` TO  `".self::getTypeTableName(array_merge($type,array('name'=>$params['name'])))."`",self::$debug)) return self::error('9','Type table rename error');
            }
        }


        $sql=(empty($params['id']) ? 'INSERT '.'INTO' : 'UPDATE')." types SET `parent`=".(empty($params['parent']) ? "NULL" : "'".$params['parent']."'").", `group`=".(empty($params['group']) ? "NULL" : "'".$params['group']."'").", `name`='".$params['name']."'";
        if(!empty($params['id'])) $sql.=" WHERE id='".$params['id']."'";
        if(!self::$db->q($sql,self::$debug)) return false;
        if(empty($params['id'])) $type['id']=self::$db->q("SELECT LAST_INSERT_ID()",self::$debug);
        if(!empty($params['view'])){
            if(!self::setTypeOpt('view',$params['view'],$type['id'])) return false;
        }

        if(empty($params['id'])) return $type['id'];
        else return true;
    }

    static function deleteType($type){
        //Deleting type elements
        $type=self::getType($type);
        self::clearType($type['id']);
        self::$db->q("DROP TABLE `".$type['table']."`",self::$debug);
        //Deleting type
        return self::$db->q("DELETE FROM `types` WHERE `id`='".$type['id']."'",self::$debug);
    }

    static function clearType($type_id){
        $elements=self::get(array('type'=>$type_id,'limit'=>false));
        foreach($elements as $element) self::delete($element['id']);
        return true;
    }

    static function setField($type,$params){
        self::debug();
        if(preg_match("/[^(\w)|(\-)]/",$params['name']))  return self::error(7,'Field name may contain only latin letters and _ or - symbols');

        $type=self::getType($type);
        $table=self::getTypeTableName($type);
        if(!self::$db->q("SHOW TABLES LIKE '".$table."'",self::$debug)){
            $sql="CREATE TABLE  ".$table." (
            element_id INT( 11 ) NOT NULL ,
            PRIMARY KEY (  element_id )
            ) ENGINE = INNODB DEFAULT CHARSET = utf8";
            self::$db->q($sql,self::$debug);
        }

        if(!empty($params['old_name'])&&$params['old_name']!=$params['name']) {
            //DROP OLD KEY
            $sql="
                SELECT k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME, i.CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS i
                LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
                WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND k.COLUMN_NAME = '".$params['old_name']."'
                AND i.TABLE_SCHEMA = DATABASE()
                AND i.TABLE_NAME = '".$table."'";
            $foreign_keys=self::$db->q($sql,self::$debug,false);
            foreach($foreign_keys as $key){
                self::$db->q('ALTER TABLE  '.$table.' DROP FOREIGN KEY  '.$key['CONSTRAINT_NAME'],self::$debug);
            }

            //Drop all indexes for this field if exist
            if($indexes=self::$db->q("SHOW INDEX FROM `".$table."` WHERE Column_name='".$params['old_name']."'",self::$debug,false)){
                foreach($indexes as $index){
                    self::$db->q("ALTER TABLE `".$table."` DROP INDEX  `".$index['Key_name']."`",self::$debug);
                }
            }
        }

        if(isset($params['hide'])&&$params['hide']!='') $extra['hide']=$params['hide'];
        if(!empty($params['placeholder'])) $extra['placeholder']=$params['placeholder'];

        if(!empty($params['multiple'])) {
            $extra['multiple']=$params['multiple'];
            $sql="ALTER TABLE  ".$table." ".($params['act']=='add' ? 'ADD' :'CHANGE `'.$params['old_name'].'`')." `".$params['name']."` ";
            $sql.='INT(11) NOT NULL';
            $sql.=(!empty($params['default']) ? " DEFAULT '".$params['default']."'" : "");
            if(!empty($extra)) $sql.=" COMMENT '".my_json_encode($extra)."'";
            if(!self::$db->q($sql,self::$debug)) return false;

            unset($extra['multiple']);
            $table='multi_'.$type['name'].'__'.$params['name'];
            if(!self::$db->q("SHOW TABLES LIKE '".$table."'",self::$debug)){
                $params['act']='add';
                $sql="CREATE TABLE  ".$table." (
                id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                element_id INT( 11 ) NOT NULL
                ) ENGINE = INNODB DEFAULT CHARSET = utf8";
                if(!self::$db->q($sql,self::$debug)) return false;
            }
        }
        elseif($params['act']!='add'){
            $mtable='multi_'.$type['name'].'__'.$params['name'];
            if(self::$db->q("SHOW TABLES LIKE '".$mtable."'",self::$debug)){
                if(!self::$db->q("DROP TABLE  `".$mtable."`",self::$debug)) return false;
            }
        }

        //Drop index and FOREIGN KEY
        if($params['act']!='add'){
            //Drop FOREIGN KEY if exist
            $sql="
                    SELECT k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME, i.CONSTRAINT_NAME
                    FROM information_schema.TABLE_CONSTRAINTS i
                    LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
                    WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY'
                    AND k.COLUMN_NAME = '".$params['name']."'
                    AND i.TABLE_SCHEMA = DATABASE()
                    AND i.TABLE_NAME = '".$table."'";
            $foreign_keys=self::$db->q($sql,self::$debug,false);
            foreach($foreign_keys as $key){
                self::$db->q('ALTER TABLE  '.$table.' DROP FOREIGN KEY  '.$key['CONSTRAINT_NAME'],self::$debug);
            }
            if($params['type']!='elements'){
                //Drop all indexes for this field if exist
                if($indexes=self::$db->q("SHOW INDEX FROM `".$table."` WHERE Column_name='".$params['name']."'",self::$debug,false)){
                    foreach($indexes as $index){
                        self::$db->q("ALTER TABLE `".$table."` DROP INDEX  `".$index['Key_name']."`",self::$debug);
                    }
                }
            }
        }

        $sql="ALTER TABLE  ".$table." ".($params['act']=='add' ? 'ADD' :'CHANGE `'.$params['old_name'].'`')." `".$params['name']."` ";
        if($params['type']=='varchar')
            $sql.='VARCHAR(255) NOT NULL';
        elseif($params['type']=='int')
            $sql.='INT(11) NOT NULL';
        elseif($params['type']=='text')
            $sql.="TEXT NOT NULL";
        elseif($params['type']=='html'){
            $sql.='TEXT NOT NULL';
            $extra['type']='html';
        }
        elseif($params['type']==='file'|$params['type']=='image'){
            $sql.='VARCHAR(255) NOT NULL';
            $extra['type']='file';
        }
        elseif($params['type']=='enum'){
            foreach($params['enum']['list'] as $i=>$item) $params['enum']['list'][$i]="'$item'";
            $sql.="ENUM(".implode(',',$params['enum']['list']).") NOT NULL";
        }
        elseif($params['type']=='date')
            $sql.='DATE NOT NULL';
        elseif($params['type']=='datetime')
            $sql.='DATETIME NOT NULL';
        elseif($params['type']=='elements'){
            $sql.='INT(11) NULL';
            $sql.=(!empty($params['default']) ? " DEFAULT '".$params['default']."'" : "");
            if(!empty($extra)) $sql.=" COMMENT '".my_json_encode($extra)."'";
            if(!empty($params['after'])) $sql.=" AFTER  `".$params['after']."`";
            if(!self::$db->q($sql,self::$debug)) return false;
            if(!self::$db->q("SHOW INDEX FROM `".$table."` WHERE Column_name='".$params['name']."'",self::$debug)){
                if(!self::$db->q('ALTER TABLE  '.$table.' ADD INDEX (`'.$params['name'].'`)',self::$debug)) return false;
            }
            $params['elements_type']=self::getTypeTableName($params['elements_type']);
            if(!self::$db->q('ALTER TABLE  '.$table.' ADD FOREIGN KEY (`'.$params['name'].'`) REFERENCES  '.$params['elements_type'].' (element_id) ON DELETE SET NULL ON UPDATE CASCADE',self::$debug)) return false;
            $sql=false;
        }
        else {
            self::error(5,'Unknown field type');
            return false;
        }

        if($sql){
            $sql.=(!empty($params['default']) ? " DEFAULT '".$params['default']."'" : "");
            if(!empty($extra)) $sql.=" COMMENT '".my_json_encode($extra)."'";
            if(!empty($params['after'])) $sql.=" AFTER  `".$params['after']."`";
            if(!self::$db->q($sql,self::$debug)) return false;
        }

        if($params['lang']!=$params['name']&&!empty($params['lang'])){
            return self::setTranslate($params['name'],$params['lang']);
        }
        self::debug(false);
        return true;
    }

    static function setTypeOpt($name,$value,$type_id){
        $opt=self::getTypeOpt($type_id,$name);
        if(is_array($value)) $value=my_json_encode($value);
        $sql=(empty($opt) ? "INSERT INTO" : "UPDATE")." `types_settings` SET `name`='$name', `value`='".$value."' ";
        if(empty($opt)) $sql.=", `type_id`='$type_id'";
        else $sql.="WHERE `name`='$name' AND `type_id`='$type_id'";
        if(!self::$db->q($sql,self::$debug)) return false;
        return true;
    }

    public static function deleteTypeField($type,$field_name){
        E::debug();
        $type=self::getType($type);
        if(is_array($field_name)){
            $return=array();
            foreach($field_name as $name){
                $return[]=self::deleteTypeField($type,$name);
            }
            return $return;
        }
        $table=self::getTypeTableName($type);
        //DROP OLD KEY
        $sql="
                SELECT k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME, i.CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS i
                LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
                WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND k.COLUMN_NAME = '".$field_name."'
                AND i.TABLE_SCHEMA = DATABASE()
                AND i.TABLE_NAME = '".$table."'";
        $foreign_keys=self::$db->q($sql,self::$debug,false);
        foreach($foreign_keys as $key){
            self::$db->q('ALTER TABLE  '.$table.' DROP FOREIGN KEY  '.$key['CONSTRAINT_NAME'],self::$debug);
        }
        return self::$db->q("ALTER TABLE ".$table." DROP `".$field_name."`",self::$debug);
    }

    static function getTypeById($type_id){
        $types=self::getTypes("id='".$type_id."'");
        if(!empty($types)) return $types[0];
        else {
            self::error(3,'Type id:'.$type_id.' not found');
            if(self::$debug) print_r(self::$errors);
            return false;
        }
    }

    static function getType($type_name_or_id){
        if(is_array($type_name_or_id)) return $type_name_or_id;
        if(is_numeric($type_name_or_id)) $type=self::getTypeById($type_name_or_id);
        else $type=self::getTypeByName($type_name_or_id);
        return $type;
    }

    static function getTypes($filter=false){
        $sql="SELECT * FROM `types` ";

        if(!empty($filter)){
            $sql.="WHERE ".$filter;
        }
        if(!$types=self::$db->q($sql,self::$debug,false)) return self::error('2','Type not found');
        foreach($types as $i=>$type) $types[$i]['table']=self::getTypeTableName($type);
        return $types;
    }

    static function getTypeOpt($type_id,$option_name=false){
        if(is_array($type_id)) $type_id=$type_id['id'];
        $sql="SELECT value FROM `types_settings` WHERE `type_id`='$type_id' ";
        if(!empty($option_name)) $sql.="AND `name`='$option_name'";

        if(!$opt=self::$db->q($sql,self::$debug)) return false;
        if($res=json_decode($opt,true)) return $res;
        else return $opt;
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
            self::error(1,'Element id:'.$element_id.' not found');
            return false;
        }
    }

    public static function getTypeByName($type_name){
        if(empty($type_name)) return self::error('3','Empty type name');
        $types=self::getTypes("name='".$type_name."'");
        if(!empty($types)) return $types[0];
        else {
            self::error(4,'Type name:'.$type_name.' not found');
            return false;
        }
    }

    public static function getTypeClass($type){
        if(!$type=self::getType($type)) return false;
        $class['name']='E';
        $class['path']=self::$root_path.'core/core.php';
        $path=self::$root_path."core/types/".$type['name'].".php";
        if(file_exists($path)) {
            $class['name']=ucfirst($type['name']);
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

    static function getField($type,$field_name){
        $type=self::getType($type);
        if(empty($type['table'])) $type['table']=self::getTypeTableName($type);
        $column=self::$db->q("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='".$type['table']."' AND COLUMN_NAME='$field_name'", self::$debug);
        $field=array();
        if(!empty($column['COLUMN_COMMENT'])) $field['comment']=json_decode($column['COLUMN_COMMENT'],true);
        if($field['comment']['multiple']) {
            $type['table']='multi_'.$type['name'].'__'.$column['COLUMN_NAME'];
            $column=self::$db->q("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='".$type['table']."' AND COLUMN_NAME='$field_name'", self::$debug);
            $field['comment']=array_merge($field['comment'],json_decode($column['COLUMN_COMMENT'],true));
        }
        $field['nullable']=($column['IS_NULLABLE']==='YES' ? true : false);
        $field['name']=$column['COLUMN_NAME'];
        $field['type']=$column['DATA_TYPE'];
        if($field['type']==='enum') {
            preg_match('/enum\((.*)\)$/', $column['COLUMN_TYPE'], $matches);
            $matches[1]=str_replace("'",'',$matches[1]);
            $field['values'] = explode(',', $matches[1]);
        }
        $field['default']=$column['COLUMN_DEFAULT'];
        $field['position']=$column['ORDINAL_POSITION'];
        $field['key']=$column['COLUMN_KEY'];
        if(!empty($field['comment'])) $field=array_merge($field, $field['comment']);
        if($field['key']=='MUL') {
            $sql="
                SELECT k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME
                FROM information_schema.TABLE_CONSTRAINTS i
                LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
                WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND k.COLUMN_NAME = '".$field['name']."'
                AND i.TABLE_SCHEMA = DATABASE()
                AND i.TABLE_NAME = '".$type['table']."'";
            $inf=self::$db->q($sql,self::$debug);
            if(!empty($inf)) {
                $field['type']='elements';
                $type_name=substr($inf['REFERENCED_TABLE_NAME'],strpos($inf['REFERENCED_TABLE_NAME'],'_')+1);
                $field['elements_type']=self::getType($type_name);
                while(!$field['elements_type']=self::getType($type_name)|(strpos('_',$inf['REFERENCED_TABLE_NAME'])===false)){
                    $type_name=substr($type_name,strpos($type_name,'_')+1);
                }
                $field['elements_type']=$type_name;
            }
        }
        if($p=strpos('_',$field['name'])!==false) $field['group']=substr($field['name'],0,$p+1);
        return $field;
    }

    static function getTypeFields($type){
        $type=self::getType($type);
        $type['table']=self::getTypeTableName($type);
        if(!self::$db->q("SHOW TABLES LIKE '".$type['table']."'")) return array();
        $fields=self::$db->q('SHOW COLUMNS FROM `'.$type['table'].'`',self::$debug);
        foreach($fields as $i=>$field){
            if($field['Field']=='element_id') unset($fields[$i]);
            else $fields[$i]=self::getField($type,$field['Field']);
        }
        return $fields;
    }

    static function getTypeFieldsNames($type){

    }

    static function getFullTypeFields($type){
        $types=self::getFullType($type);
        $allFields=array();
        foreach($types as $type){
            $fields=self::getTypeFields($type);
            $allFields=array_merge($allFields,$fields);
        }
        return $allFields;
    }

    static function getTypeGroups($filter=false){
        $sql="SELECT *,id-previous AS sort FROM `type_groups` ";
        if(!empty($filter)){
            $sql.="WHERE ".$filter;
        }
        $sql.="ORDER BY sort";
        return self::$db->q($sql,self::$debug,false);
    }

    static function getConnectedTypes($type){
        $types=self::getFullType($type);
        $tables=array();
        foreach($types as $t) $tables[]=self::getTypeTableName($t);
        $sql="
        SELECT i.TABLE_NAME, k.COLUMN_NAME, k.REFERENCED_TABLE_NAME
        FROM information_schema.TABLE_CONSTRAINTS i
        LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
        WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY'
        AND i.TABLE_SCHEMA = DATABASE()";
        if(!empty($tables)){
            $sql.=" AND (";
            foreach($tables as $i=>$table) $sql.=($i ? " OR" : "")." k.REFERENCED_TABLE_NAME = '".$table."'";
            foreach($types as $type) $sql.=" OR k.REFERENCED_TABLE_NAME LIKE 'multi_".$type['name']."__%'";
            $sql.=")";
        }
        $keys=self::$db->q($sql,self::$debug,false);
        $connected_types=array();
        foreach($keys as $key){
            //if(strpos('multy_',$key['TABLE_NAME'],))
            $connected['type']=self::getType(str_replace('et_','',$key['TABLE_NAME']));
            $connected['field']=$key['COLUMN_NAME'];
            $connected_types[]=$connected;
        }
        return $connected_types;
    }

    static function getSubTypesList($type){
        self::getSubTypesRecursive($type);
        return self::$recursion_temp;
    }
    static function getSubTypesRecursive($type){
        $type=self::getType($type);
        $sub_types=self::$db->q("SELECT * FROM `types` WHERE `parent`='".$type['id']."'",self::$debug,false);
        foreach($sub_types as $sub_type){
            self::$recursion_temp[]=$sub_type;
            self::getSubTypesRecursive($sub_type);
        }
    }

    public static function translate($text,$ucfirst='auto',$lang='en'){
        mb_internal_encoding('utf-8');
        $text=trim((string)$text);
        $text=str_replace('_',' ',$text);
        if($ucfirst==='auto') $ucfirst=ctype_upper(substr($text,0,1));
        if($lang==self::$lang) { //Do not translate
            if($ucfirst) return mb_ucfirst($text);
            else return mb_strtolower($text);
        }
        $sql="SELECT `".self::$lang."` FROM `lang` WHERE `".$lang."`='".$text."' ORDER BY `app` LIMIT 0,1";
        if($translate=self::$db->q($sql)) {
            $text=($ucfirst ? mb_ucfirst($translate) : mb_strtolower($translate));
        }
        else{
            $translate=self::translate_yandex($text);
            if(!empty($translate)) {
                //if($translate==$text) return $translate;
                self::$db->q("INSERT INTO `lang` SET `".$lang."`='".$text."', `".self::$lang."`='".$translate."'",self::$debug);
                $text=$translate;
            }
            /*//Try translate by word
                $words=explode(' ',$text);
                $text='';
                $text=self::translate($words[0],$ucfirst).' '; //Use $ucfirst to first word
                unset($words[0]);
                foreach($words as $word) $text.=self::translate($word).' ';
            }*/
        }
        if(!empty($_COOKIE['langMenu'])) $text='<span class="lang">'.$text.'</span>';
        return trim($text);
    }

    static function setTranslate($en,$translate,$lang=''){
        $en=trim(str_replace('_',' ',$en));
        if(empty($lang)) $lang=self::$lang;
        if(!empty($translate)){
            $sql="SELECT count(*) FROM `lang` WHERE `en`='$en'";
            if($exist=self::$db->q($sql,self::$debug)) return self::$db->q("UPDATE `lang` SET `".self::$lang."`='$translate' WHERE `en`='$en'",self::$debug);
            else return self::$db->q("INSERT INTO `lang` SET `".self::$lang."`='$translate', `en`='$en'",self::$debug);
        }
        else return false;
    }

    static function translate_yandex($text){
        $key='trnsl.1.1.20140304T063512Z.016dd2d94d77fdec.a75eaf9d47041ea6d7d086977a7249b71d910dd5';
        $url='https://translate.yandex.net/api/v1.5/tr.json/translate?key='.$key.'&text='.$text.'&lang=en-ru';

        if(!class_exists('REST')) include(CMS_DIR.'core/rest.class.php');
        $R= new REST;
        $resp=$R->get($url);
        return $resp['text'][0];
    }

    /**
     * Memorize callback to buffer
     *
     * @param array $callback Callback to memorize
     *
     */
    static function add_to_buffer($callback){
        $params = func_get_args();
        unset($params[0]);

        self::$buffer[]=ob_get_contents();
        ob_clean();
        self::$buffer_functions[]=array(
            'function'=>$callback,
            'params'=>$params
        );
    }

    /**
     * Execute memorizated functions to render final output
     *
     * @param string $content Callback to memorize
     *
     * @return string
     */
    static function end_buffer($content){
        $return='';
        foreach(self::$buffer_functions as $i=>$val){
            $return.=self::$buffer[$i].forward_static_call_array(self::$buffer_functions[$i]['function'],self::$buffer_functions[$i]['params']);
        }
        return $return.$content;
    }

    static function component($component,$template='default',$params=array()){
        if(file_exists(include(COMPONENTS_DIR.$component."/component.php"))){
            include(COMPONENTS_DIR.$component."/component.php");
            return true;
        }
        //self::$errors[]="Component $component not found";
        return false;
    }
}
?>