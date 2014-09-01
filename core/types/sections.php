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
    public static $children=array();

    static function init(){
        if(!empty($_GET['section'])){
            if($section=self::getByPath($_GET['section'])) {
                header("HTTP/1.0 200 OK");
                self::$section=$section;
            }
            else {
                header('HTTP/1.0 404 Not Found');
                S::$section['template']='404';
            }
        }
        else self::$section=self::getByPath('main');

        //Access check
        if(!empty(self::$section['access'])){
            if(U::$user['group']['id']!=self::$section['access']&&U::$user['group']['parent']!=self::$section['access']){
                header('HTTP/1.0 401 - Access denied');
                S::$section['template']='login';
            }
        }

        self::$section['parents']=self::getParents(S::$section['id']);
        foreach(self::$section['parents'] as $i=>$parent) self::$section['parents'][$i]=S::getById($parent);
        self::$section['parents']=array_reverse(self::$section['parents']);
    }

    static function get($params=array()){
        $params['type']='sections';
        $params['order']='order';
        $sections=parent::get($params);
        if(!empty($params['parent_id'])){
            $parent=self::getById($params['parent_id']);
        }
        foreach($sections as $i=>$section){
            if(empty($section['link'])) $sections[$i]['link']='/'.$section['path'].'/';
            if(empty($section['template'])&&!empty($parent)) $sections[$i]['template']='/'.$parent['template'].'/';
            if(S::$section['id']==$section['id']) $sections[$i]['selected']=true;
        }
        return $sections;
    }

    static function getSelectList($params){
        if(!$elements=self::get($params)) return false;
        foreach($elements as $i=>$element){
            $elements[$i]['option_name']=$element['name'];
        }
        return $elements;
    }

    static function set($params){
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

        $params['type']=1;
        if(empty($params['path'])) $params['path']=mb_strtolower(translit($params['name']));
        $c=parent::count(array(
            'type'=>1,
            'filter'=>"path='".$params['path']."'".(!empty($params['id']) ? " AND element_id!='".$params['id']."'" : "")
        ));
        if($c) return self::error('4','Path '.$params['path'].' used by another section');
        return parent::set($params);
    }

    static function getById($section_id){
        $section=parent::getById($section_id);
        if(empty($section['template'])) $section['template']=self::getTemplate($section_id);
        return $section;
    }

    static function getByPath($section_path){
        $params['filter']="path='".$section_path."'";
        if(!$sections=self::get($params)) return false;
        if(empty($sections[0]['template'])) $sections[0]['template']=self::getTemplate($sections[0]['id']);
        return $sections[0];
    }

    static function getList($parent_id=''){
        //E::debug();
        if(empty($parent_id)) $params['filter']="parent_id is NULL";
        else $params['filter']="parent_id='".$parent_id."'";
        $params['filter'].=" AND `show`=1";
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

    static function children($parent_id=0){
        $sections=self::getList($parent_id);
        if(empty($sections)) return false;
        foreach($sections as $section){
            self::$children[]=$section;
            self::children($section['id']);
        }
        return self::$children;
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