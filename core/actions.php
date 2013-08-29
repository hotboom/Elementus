<?
if(!empty($_GET['act'])){
    switch($_GET['act']){
        case 'logout':
            Users::logout();
            header('location: /');
        break;

        case 'login':
            //E::debug();
            if(!Users::login($_POST['email'],$_POST['password'],$_POST['remember'])) {
                header('HTTP/1.0 403 Forbidden');
                exit();
            }
        break;
    }
}
?>