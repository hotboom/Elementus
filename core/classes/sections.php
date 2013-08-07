<?
class Sections extends Elements{
    public static function get($params=array()){
        $params['type']='sections';
        return parent::get($params);
    }

    public static function set($params){
        $params['type']=1;
        return parent::set($params);
    }

    public static function getById($section_id){
        $params['filter']="element_id='".$section_id."'";
        $sections=self::get($params);
        return $sections[0];
    }
    public static function getByPath($section_path){
        $params['filter']="path='".$section_path."'";
        $sections=self::get($params);
        return $sections[0];
    }

    public static function getList($parent_id='NULL'){
        if(empty($parent_id)) $params['filter']="parent_id is NULL";
        else $params['filter']="parent_id='".$parent_id."'";
        return self::get($params);
    }
}
?>