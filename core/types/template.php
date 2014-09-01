<?
class T extends E{
    static $id;
    static $path;
    static $name;
    static $templates_dir='templates';

    static function init(){
        self::$id=parent::$template['id'];
        self::$name=parent::$template['name'];
        self::$path=self::$templates_dir.'/'.parent::$template['path'];
    }

    static function page($params){
        $params['type']=1;
        return parent::set($params);
    }

    static function render($template,$data=array()){
        global $root_path;
        include($template);
    }
}
class_alias('T', 'Template');
?>