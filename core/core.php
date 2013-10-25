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
 * @author  Mike van Riel <huntedbox@gmail.com>
 *
 * @since 1.0
 */
class E{
    /** @type object Database object */
    public static $db;
    /** @type array Current error */
    public static $error=array();
    /** @type bool Debug mode on/off */
    public static $debug;
    /** @type array Current app params array */
    public static $app;
    /** @type string Current app params array */
    public static $foreign_select='select';
    /** @type string Path to Elementus root directory */
    public static $root_path;
    /** @type string Interface language */
    public static $lang='ru';
    /** @type string Template name */
    public static $template;

    /** @type mixed Temp variable for recursion functions results */
    private static $recursion_temp=false;

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

    private static function error($error){
        self::$error=$error;
        return false;
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
            $sql.="AND (";
            $sql.=$params['filter'];
            $sql.=") ";
        }
        if(!empty($params['order'])) {
            if(is_array($params['order'])) $sql.="ORDER BY `".$params['order'][0]."` ".($params['order'][1] ? 'DESC' : 'ASC')." ";
            else $sql.="ORDER BY `".$params['order']."` ";
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
                self::$error['code']=2;
                self::$error['desc']='Missing element type name or id';
                if(self::$debug) print_r(self::$error);
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
                $sql.=", `".$field['name']."`=";
                if(empty($field['val'])&&$field['nullable']) $sql.="NULL";
                else $sql.="'".$field['val']."'";
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

    static function setType($params){
        $type=$params;
        if(empty($params['name'])) return self::error(array('code'=>6,'Type name is empty'));
        if(preg_match("/[^(\w)|(\-)]/",$params['name']))  return self::error(array('code'=>7,'Type name may contain only latin letters and _ or - symbols'));
        if(!empty($params['view'])&&!is_array($params['view'])) return self::error(array('code'=>'8','Incorrect view format'));

        $sql=(empty($params['id']) ? 'INSERT '.'INTO' : 'UPDATE')." types SET `parent`=".(empty($params['parent']) ? "NULL" : "'".$params['parent']."'").", `group`=".(empty($params['group']) ? "NULL" : "'".$params['group']."'").", `name`='".$params['name']."'";
        if(!empty($params['id'])) $sql.=" WHERE id='".$params['id']."'";
        if(!self::$db->q($sql,self::$debug)) return false;
        if(empty($params['id'])) $type['id']=self::$db->q("SELECT LAST_INSERT_ID()",self::$debug);
        if(!empty($params['view'])){
            $view=self::getTypeView($type['id']);
            $sql=(empty($view) ? "INSERT " : "UPDATE ")."`types_settings` SET `name`='view', `value`='".json_encode($params['view'])."' ";
            if(empty($view)) $sql.=", `type_id`='".$type['id']."'";
            else $sql.="WHERE `type_id`='".$type['id']."'";
            if(!self::$db->q($sql,self::$debug)) return false;
        }

        if(empty($params['id'])) return $type['id'];
        else return true;
    }

    static function setField($type,$params){
        if(preg_match("/[^(\w)|(\-)]/",$params['name']))  return self::error(array('code'=>7,'Field name may contain only latin letters and _ or - symbols'));

        $type=self::getType($type);
        $table=self::getTypeTableName($type);
        if(!self::$db->q("SHOW TABLES LIKE '".$table."'",self::$debug)){
            $sql="CREATE TABLE  ".$table." (
            element_id INT( 11 ) NOT NULL ,
            PRIMARY KEY (  element_id )
            ) ENGINE = INNODB DEFAULT CHARSET = utf8";
            self::$db->q($sql,self::$debug);
        }
        if(!empty($params['old_name'])) {
            $field=self::getField($type,$params['old_name']);
            if($field['type']==='elements'){
                //DROP OLD KEY
                $sql="
                    SELECT k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME, i.CONSTRAINT_NAME
                    FROM information_schema.TABLE_CONSTRAINTS i
                    LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
                    WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY'
                    AND k.COLUMN_NAME = '".$field['name']."'
                    AND i.TABLE_SCHEMA = DATABASE()
                    AND i.TABLE_NAME = '".$table."'";
                $foreign_keys=self::$db->q($sql,self::$debug,false);
                foreach($foreign_keys as $key){
                    self::$db->q('ALTER TABLE  '.$table.' DROP FOREIGN KEY  '.$key['CONSTRAINT_NAME'],self::$debug);
                }
            }
        }

        $alter_prx="ALTER TABLE  ".$table." ".($params['act']=='add' ? 'ADD' :'CHANGE `'.$params['old_name'].'`')." `".$params['name']."` ";
        if($params['type']=='string'){
            if(!self::$db->q($alter_prx.'VARCHAR(255) NOT NULL',self::$debug)) return false;
        }
        elseif($params['type']=='int'){
            if(!self::$db->q($alter_prx.'INT(11) NOT NULL',self::$debug)) return false;
        }
        elseif($params['type']=='text'){
            if(!self::$db->q($alter_prx."TEXT NOT NULL",self::$debug)) return false;
        }
        elseif($params['type']=='html'){
            if(!self::$db->q($alter_prx.'TEXT NOT NULL COMMENT \'{"type":"html"}\'',self::$debug)) return false;
        }
        elseif($params['type']==='file'|$params['type']=='image'){
            if(!self::$db->q($alter_prx.'VARCHAR(255) NOT NULL COMMENT \'{"type":"file"}\'',self::$debug)) return false;
        }
        elseif($params['type']=='enum'){
            foreach($params['enum']['list'] as $i=>$item) $params['enum']['list'][$i]="'$item'";
            if(!self::$db->q($alter_prx."ENUM(".implode(',',$params['enum']['list']).") NOT NULL",self::$debug)) return false;
        }
        elseif($params['type']=='elements'){
            if(!self::$db->q($alter_prx.'INT(11) NULL',self::$debug)) return false;
            if(!self::$db->q('ALTER TABLE  '.$table.' ADD INDEX (`'.$params['name'].'`)',self::$debug)) return false;
            $params['elements_type']=self::getTypeTableName($params['elements_type']);
            echo $params['elements_type'];
            //ALTER TABLE et_content_products ADD FOREIGN KEY (brand) REFERENCES et_content_phones (`element_id`) ON DELETE SET NULL ON UPDATE CASCADE;
            //ALTER TABLE et_content_products ADD FOREIGN KEY (brand) REFERENCES et_content_products_phones (element_id) ON DELETE SET NULL ON UPDATE CASCADE
            if(!self::$db->q('ALTER TABLE  '.$table.' ADD FOREIGN KEY (`'.$params['name'].'`) REFERENCES  '.$params['elements_type'].' (element_id) ON DELETE SET NULL ON UPDATE CASCADE',self::$debug)) return false;
        }
        else {
            self::$error['code']=5;
            self::$error['desc']='Unknown field type';
            return false;
        }

        if($params['lang']!=$params['name']&&!empty($params['lang'])){
            return self::setTranslate($params['name'],$params['lang']);
        }
        return true;
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
        return self::$db->q("ALTER TABLE ".$table." DROP `".$field_name."`",self::$debug);
    }

    static function getTypeById($type_id){
        $types=self::getTypes("id='".$type_id."'");
        if(!empty($types)) return $types[0];
        else {
            self::$error['code']=3;
            self::$error['desc']='Type id:'.$type_id.' not found';
            if(self::$debug) print_r(self::$error);
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
        return self::$db->q($sql,self::$debug,false);
    }

    static function getTypeView($type_id){
        if(is_array($type_id)) $type_id=$type_id['id'];
        $view=self::$db->q("SELECT value FROM `types_settings` WHERE `name`='view' AND `type_id`='$type_id'",self::$debug);
        if(!empty($view)) {
            $view=json_decode($view,true);
            $view['fields']=explode(',',$view['fields']);
            foreach($view['fields'] as $i=>$field_name) $view['fields'][$i]=trim($field_name);
            return $view;
        }
        else return false;
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

    static function getField($type,$field_name){
        $type=self::getType($type);
        if(empty($type['table'])) $type['table']=self::getTypeTableName($type);
        $column=self::$db->q("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='".$type['table']."' AND COLUMN_NAME='$field_name'", self::$debug);
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
        if(!empty($column['COLUMN_COMMENT'])) {
            $field['comment']=json_decode($column['COLUMN_COMMENT'],true);
            if(!empty($field['comment']['type'])) $field['type']=$field['comment']['type'];
        }
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
                $field['elements_type']=substr($inf['REFERENCED_TABLE_NAME'],strripos($inf['REFERENCED_TABLE_NAME'],'_')+1);
            }
        }
        return $field;
    }

    static function getTypeFields($type){
        $type=self::getType($type);
        $type['table']=self::getTypeTableName($type);
        if(!self::$db->q("SHOW TABLES LIKE '".$type['table']."'")) return array();
        $fields=self::$db->q('SHOW COLUMNS FROM `'.$type['table'].'`',self::$debug);
        foreach($fields as $i=>$field){
            if($field['Field']=='element_id') unset($fields[$i]);
            else $fields[$i]=self::getField($type,$field['Field'],$table);
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
            $sql.=")";
        }
        $keys=self::$db->q($sql,self::$debug,false);

        $connected_types=array();
        foreach($keys as $key){
            $connected['type']=self::getType(substr($key['TABLE_NAME'],strripos($key['TABLE_NAME'],'_')+1));
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
        if($ucfirst==='auto') $ucfirst=ctype_upper(substr($text,0,1));
        if($lang==self::$lang) { //Do not translate
            if($ucfirst) return mb_ucfirst($text);
            else return mb_strtolower($text);
        }
        $sql="SELECT `".self::$lang."` FROM `lang` WHERE `".$lang."`='".$text."' ORDER BY `app` LIMIT 0,1";
        if($translate=self::$db->q($sql)) {
            $text=($ucfirst ? mb_ucfirst($translate) : mb_strtolower($translate));
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

    static function setTranslate($en,$translate,$lang=''){
        if(empty($lang)) $lang=self::$lang;
        if(!empty($translate)){
            $sql="SELECT count(*) FROM `lang` WHERE `en`='$en' AND `$lang`='$translate' AND `app`='".self::$app['id']."'";
            if($exist=self::$db->q($sql,self::$debug)) return self::$db->q("UPDATE `lang` SET `".self::$lang."`='$translate' WHERE `en`='$en' AND `app`='".self::$app."'",self::$debug);
            else return self::$db->q("INSERT INTO `lang` SET `".self::$lang."`='$translate', `en`='$en', `app`='".self::$app['id']."'",self::$debug);
        }
        else return false;
    }
}
?>