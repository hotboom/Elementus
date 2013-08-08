<?
class Users extends Elements{
    public static function set($params){
        $params['type']='users';
        if($params['new_password']) $params['password']=self::hashPassword($params['new_password'],$params['regdate']);
        return parent::set($params);
    }
    public static function hashPassword($password,$regdate){
        $regdate=str_replace('4','r',$regdate); //paranoia
        $regdate=str_replace('2','x',$regdate); //paranoia
        return substr(md5($password.md5($regdate)),0,16);
    }
}
?>