<?
/**
 * Sections core class
 *
 * @author  Andrey Nedorostkov <huntedbox@gmail.com>
 *
 * @since 1.0
 */
class S extends E{
    public static $section;

    static function init(){
        if(!empty($_GET['section'])){
            if($section=self::getByPath($_GET['section'])) {
                header("HTTP/1.0 200 OK");
                self::$section=$section;
            }
            else header('HTTP/1.0 404 Not Found');
        }
        else self::$section=self::getByPath('main');
    }

    static function get($params=array()){
        $params['type']='sections';
        return parent::get($params);
    }

    static function set($params){
        $params['type']=1;
        if(empty($params['path'])) $params['path']=translit($params['name']);
        return parent::set($params);
    }

    static function getById($section_id){
        $section=parent::getById($section_id);
        if(empty($section['template'])) $section['template']=self::getTemplate($section_id);
        return $section;
    }

    static function getByPath($section_path){
        $params['filter']="path='".$section_path."'";
        $sections=self::get($params);
        if(empty($sections[0]['template'])) $sections[0]['template']=self::getTemplate($sections[0]['id']);
        return $sections[0];
    }

    static function getList($parent_id=''){
        //E::debug();
        if(empty($parent_id)) $params['filter']="parent_id is NULL";
        else $params['filter']="parent_id='".$parent_id."'";
        $params['order']='order';
        return self::get($params);
    }

    static function getParents($section){
        $parents=array();
        if(!is_array($section)) $section=parent::getById($section);
        while($section['parent_id']){
            if($section=parent::getById($section['parent_id'])) $parents[]=$section['id'];
        }
        return $parents;
    }

    static function getTemplate($section){
        $parents=self::getParents($section); 
        foreach($parents as $parent){
            $parent=parent::getById($parent);
            if(!empty($parent['template'])) return $parent['template'];
        }
        return false;
    }

    static function section($field){
        return self::$section[$field];
    }

    static function getTitle(){
        if(empty(self::$section['title'])) return self::$section['name'];
        else return self::$section['title'];
    }

    static function show($field){
        self::add_to_buffer(array('S','section'),$field);
    }
}

class_alias('S', 'Sections');
?>