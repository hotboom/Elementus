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
        $params['filter']=array('element_id'=>$section_id);
        return self::get($params);
    }

    public static function getList($parent_id='NULL'){
        $params['filter']=array('parent_id'=>$parent_id);
        return self::get($params);
    }
}
?>