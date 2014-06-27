<?
if(!empty($_GET['act'])){
    switch($_GET['act']){
        case 'logout':
            U::logout();
            header('location: /');
        break;

        case 'login':
            if(!U::login($_POST['email'],$_POST['password'],$_POST['remember'])) {
                header('HTTP/1.0 403 Forbidden');
            }
            else header ('Location: '.$_SERVER['HTTP_REFERER']);
        break;

        case 'signup':
            $params=array();
            $params['name']=htmlspecialchars($_POST['name']);
            $params['email']=htmlspecialchars($_POST['email']);
            $params['password']= $_POST['password'];
            $params['regdate']=date('Y-m-d H:i');

            if(U::set($params)) {
                header ('HTTP/1.1 301 Moved Permanently');
                header ('Location: /reg_successful');
                exit();
            }

        break;
    }
}
?>