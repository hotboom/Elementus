<?
class U extends E{
    static $user;

    static function init(){
        if(session_id()=='') session_start();
        if(!self::$user=self::autorization()) self::$user['hash']=session_id();
    }

    static function set($params){
        $params['type']='users';

        if(empty($params['id'])){
            $users=E::get(array(
                'type'=>'users',
                'filter'=>array('email'=>$params['email'])
            ));
            if(!empty($users)) {
                parent::error('12','Пользователь с таким e-mail адресом уже зарегистрирован');
                return false;
            }
        }
        else{
            $users=array();
            $users[]=E::getById($params['id']);
        }

        if(!empty($params['new_password'])) $params['password']=self::hashPassword($params['new_password'],$users[0]['email']);
        return parent::set($params);
    }

    static function hashPassword($password,$salt){
        $salt=mb_strtolower($salt);
        $salt=str_replace('4','r',$salt); //paranoia
        $salt=str_replace('2','x',$salt); //paranoia
        return substr(md5($password.md5($salt)),0,32);
    }

    static function autorization(){
        if(!empty($_COOKIE['key'])){
            $params=array('type'=>'users','filter'=>"password='".$_COOKIE['key']."'");
            if($users=parent::get($params)) {
                $users[0]['hash']=$users[0]['password'];
                $users[0]['group']=parent::getById($users[0]['group_id']);
                return $users[0];
            }
            return false;
        }
        return false;
    }

    static function login($email, $password, $remember=false){
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
        self::error('1','Wrong username or password');
        return false;
    }

    static function logout(){
        $domain=str_replace('www.','',$_SERVER['SERVER_NAME']);
        setcookie('key','',0,'/',$domain);
    }
    
    static function restore_password($email){
        $users=E::get(array('type'=>'users','filter'=>array(
            'email'=>$email
        )));
        if(empty($users)) {
            E::error('12','User not found;');
            return false;
        }
        $user=$users[0];
        $key=md5(time());
        E::set(array('id'=>$user['id'],'recovery_key'=>$key));
        $body=t("To recover your password, please follow link below:");
        $body.="<br>http://".$_SERVER['HTTP_HOST']."/profile/key/".$key;
        send_mime_mail(
            $_SERVER['HTTP_HOST'], // имя отправителя
            'no-reply@'.$_SERVER['HTTP_HOST'], // email отправителя
            $user['name'], // имя получателя
            $user['email'], // email получателя
            'utf-8', // кодировка переданных данных
            'utf-8', // кодировка письма
            t('Password recovery on ').$_SERVER['HTTP_HOST'], // тема письма
            $body, // текст письма
            'text/html'
        );
        return $user;
    }
}

class_alias('U', 'Users');
?>