<?
class Template extends Elements{
    public static $template;
    public static $templates_dir='templates';

    public static function init($template_name){
        $filter=array('name',$template_name);
        self::$template=parent::get('templates',array('filter'=>$filter));
    }

    public static function header($params){
       include(self::$$templates_dir.'/'.self::$template['name'].'/header.php');
    }

    public static function page($params){
        $params['type']=1;
        return parent::set($params);
    }

    public static function footer($section_id){
        $params['filter']=array('element_id'=>$section_id);
        return self::get($params);
    }
}
?>