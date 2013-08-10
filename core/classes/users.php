<?
class Users extends Elements{
    public static $user;

    public static function init(){
        self::$user=self::autorization();
    }
    public static function set($params){
        $params['type']='users';
        if($params['new_password']) $params['password']=self::hashPassword($params['new_password'],$params['email']);
        return parent::set($params);
    }

    public static function hashPassword($password,$salt){
        $salt=str_replace('4','r',$salt); //paranoia
        $salt=str_replace('2','x',$salt); //paranoia
        return substr(md5($password.md5($salt)),0,32);
    }
    public static function autorization(){
        if(!empty($_COOKIE['key'])){
            $params=array('type'=>'users','filter'=>"password='".$_COOKIE['key']."'");
            if($users=parent::get($params)) return $users[0];
            return false;
        }
        return false;
    }
    public static function login($email, $password, $remember=false){
        $hash=self::hashPassword($password,$email);
        $params=array('type'=>'users','filter'=>"password='".$hash."'");
        if($users=parent::get($params)) {
            if($remember) $exp=time()+3600*24*14;
            else $exp=0;
            $domain=str_replace('www.','',$_SERVER['SERVER_NAME']);
            setcookie('key',$hash,$exp,'/',$domain);
            self::$user=$users[0];
            return true;
        }
        return false;
    }
    public static function logout(){
        $domain=str_replace('www.','',$_SERVER['SERVER_NAME']);
        setcookie('key','',0,'/',$domain);
        header('location: /index.php');
    }
}
?>