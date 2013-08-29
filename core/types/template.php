<?
class Template extends E{
    public static $id;
    public static $path;
    public static $name;
    public static $templates_dir='templates';

    public static function init(){
        self::$id=parent::$template['id'];
        self::$name=parent::$template['name'];
        self::$path=parent::$template['path'];
    }

    public static function header($params){
       include(self::$templates_dir.'/'.self::$path.'/header.php');
    }

    public static function page($params){
        $params['type']=1;
        return parent::set($params);
    }

    public static function footer($section_id){
        $params['filter']="element_id='".$section_id."'";
        return self::get($params);
    }
}
?>