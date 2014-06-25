<?
$result['step']=1;

//Adding product to cart
if(!empty($_GET['add'])){
    if(empty($_COOKIE)){
        header('HTTP/1.0 403 Forbidden');
        echo 'Ошибка: Включите подержку файлов Cookie.';
        exit;
    }

    if(empty($_GET['num'])) $num=1;
    else $num=(int)$_GET['num'];

    $offer_id=(int)$_GET['add'];

    //Проверка существования товара
    if(!$offer=E::getById($offer_id)) {
        header('HTTP/1.0 403 Forbidden');
        echo 'Ошибка: Товар не найден';
        exit;
    }
    if($offer['type_id']!=20) {
        header('HTTP/1.0 403 Forbidden');
        echo 'Ошибка: Неверный код товара';
        exit;
    }

    //Проверяем существование у пользователя неотправленного заказа и создаем новый если такого нет
    if(!empty(U::$user['hash'])) $orders=E::get(array('type'=>22,'filter'=>array('hash'=>U::$user['hash'],'status'=>'not send')));
    else{
        header('HTTP/1.0 403 Forbidden');
        echo 'Ошибка авторизации, обратитесь к администратору';
        exit;
    }

    if(empty($orders)){
        $order_id=E::set(array(
            'type'=>22,
            'user'=>U::$user['id'],
            'date'=>date('Y-m-d H:i'),
            'hash'=>U::$user['hash'],
            'ip'=>$_SERVER['REMOTE_ADDR']
        ));

        //Чистим неотправленные заказы старше 30-ти дней
        //E::debug();
        $orders_trash=E::get(array(
            'type'=>22,
            'filter'=>'`status`="not send" AND `date`<"'.date('Y-m-d',time()-(3600*24*30)).'"',
            'limit'=>false
        ));
        foreach($orders_trash as $order_tr){
            $offers_tr=E::get(array('type'=>23,'filter'=>array(
                'order'=>$order_tr['id']
            )));
            foreach($offers_tr as $offer_tr) {
                E::delete($offer_tr['id']);
            }
            E::delete($order_tr['id']);
        }
    }
    else $order_id=$orders[0]['id'];

    //Добавляем позицию в заказ если ее нет
    $added=E::get(array('type'=>23,'filter'=>array('offer'=>$offer_id,'order'=>$order_id)));
    if(empty($added)){
        E::set(array(
            'type'=>23,
            'order'=>$order_id,
            'offer'=>$offer_id,
            'num'=>$num
        ));
    }
    else {
        //E::debug();
        E::set(array('id'=>$added[0]['id'],'num'=>$added[0]['num']+1));
    }
}

//Удаление товара из корзины
if(!empty($_GET['delete'])){
    $offers=$_GET['delete'];
    $order=E::get(array('type'=>22,'filter'=>array('hash'=>U::$user['hash'])));
    $order_id=$order[0]['id'];
    foreach($offers as $offer){
        E::delete((int)$offer);
    }
}

$paymethods=E::getField('orders','paymethod');
$result['paymethods']=$paymethods['values'];

$delivery=E::getField('orders','delivery');
$result['delivery']=$delivery['values'];

$orders=E::get(array('type'=>22,'filter'=>array('hash'=>U::$user['hash'],'status'=>'not send')));
$result['order']=$orders[0];

if(!empty(U::$user['id'])) {
    $result['addresses']=E::get(array(
        'type'=>'address',
        'filter'=>array('user'=>U::$user['id'])
    ));
}

$result['offers']=E::get(array('type'=>23,'filter'=>array('order'=>$result['order']['id'])));

foreach($result['offers'] as $i=>$offer) {
    if($product=E::getById($offer['offer'])){
        $result['offers'][$i]=array_merge($product,$offer);
    };
}

//Обновление заказа
if($_REQUEST['step']==2){
    //Сохранение пользователя
    if(empty(U::$user['id'])) $password=substr(md5(mktime()),0,8);
    else $password=false;
    U::set(array(
        'id'=>U::$user['id'],
        'name'=>htmlspecialchars($_POST['user']['name']),
        'email'=>$_POST['user']['email'],
        'phone'=>htmlspecialchars($_POST['user']['phone']),
        'new_password'=>$password
    ));
    if(empty(U::$user['id'])){
        U::login($_POST['user']['email'],$password,true);
    }

    //Save order
    E::set(array(
        'id'=>$result['order']['id'],
        'user'=>U::$user['id'],
        'paymethod'=>$_POST['paymethod'],
        'delivery'=>$_POST['delivery'],
        'status'=>'sended'
    ));

    //Save address
    $addresses=E::get(array(
        'type'=>'address',
        'filter'=>array('user'=>U::$user['id'])
    ));
    E::set(array(
        'id'=>(empty($addresses) ? false : $addresses[0]['id']),
        'type'=>'address',
        'user'=>U::$user['id'],
        'country'=>$_POST['address']['country'],
        'region'=>$_POST['address']['region'],
        'city'=>$_POST['address']['city'],
        'street'=>$_POST['address']['street'],
        'address'=>$_POST['address']['address']
    ));

    $body=E::get(array('type'=>28,'filter'=>array(
        'name'=>'order_admin'
    )));
    $body=$body[0]['content'];
    $body=str_replace('#order#',$result['order']['id'],$body);
    $body=str_replace('#date#',date('d.m.y H:i'),$body);
    $body=str_replace('#user#',htmlspecialchars($_POST['user']['name']).', '.htmlspecialchars($_POST['user']['email']).', '.htmlspecialchars($_POST['user']['phone']),$body);
    $body=str_replace('#delivery#',htmlspecialchars($_POST['delivery']),$body);
    $body=str_replace('#paymethod#',htmlspecialchars($_POST['paymethod']),$body);
    $body=str_replace('#comment#',htmlspecialchars($_POST['comment']),$body);
    /*$body='<h2><a href="http://'.$_SERVER['HTTP_HOST'].'/admin/#/type/id/4/filter[id]/'.U::$user['id'].'">Заказчик</a></h2>';
    $body.='имя: '.htmlspecialchars($_POST['user']['name']).'<br>';
    $body.='e-mail: '.htmlspecialchars($_POST['user']['email']).'<br>';
    $body.='телефон: '.htmlspecialchars($_POST['user']['phone']).'<br>';
    $body.='доставка: '.htmlspecialchars($_POST['delivery']).'<br>';
    $body.='оплата: '.htmlspecialchars($_POST['paymethod']).'<br>';
    $body.='<h2><a href="http://'.$_SERVER['HTTP_HOST'].'/admin/#/type/id/22/filter[id]/'.$result['order']['id'].'">Заказ</a></h2>';
    $body.='<table><tr>';*/
    $offers='<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
    $fields=array('articul','name','num','unit','price');
    foreach($fields as $field) $offers.='<th style="padding: 0px 12px 25px 0px; font-size: 11px; line-height: 22px; font-family: Helvetica, Arial, sans-serif; color: #999999;;">'.t($field).'</th>';
    $offers.='</tr>';

    foreach($result['offers'] as $offer){
        $offers.='<tr>';
        foreach($fields as $i=>$field) {
            $offers.='<td style="padding: 0px 12px 25px 0px; font-size: 14px; line-height: 22px; font-family: Helvetica, Arial, sans-serif; color: #ffffff;">'.$offer[$field].'</td>';
        }
        $offers.='</tr>';
    }
    $offers.='</table>';
    $body=str_replace('#offers#',$offers,$body);

    $users=E::get(array(
        'type'=>'users',
        'filter'=>array('group_id'=>19)
    ));

    //Notice to administrators
    foreach($users as $user){
        send_mime_mail(
            $_SERVER['HTTP_HOST'], // имя отправителя
            'no-reply@'.$_SERVER['HTTP_HOST'], // email отправителя
            $user['name'], // имя получателя
            $user['email'], // email получателя
            'utf-8', // кодировка переданных данных
            'utf-8', // кодировка письма
            'Заказ на '.$_SERVER['HTTP_HOST'], // тема письма
            $body, // текст письма
            'text/html'
        );
    }

    //Notice to buyer
    send_mime_mail(
        $_SERVER['HTTP_HOST'], // имя отправителя
        'no-reply@'.$_SERVER['HTTP_HOST'], // email отправителя
        $_POST['user']['name'], // имя получателя
        $_POST['user']['email'], // email получателя
        'utf-8', // кодировка переданных данных
        'utf-8', // кодировка письма
        'Заказ на '.$_SERVER['HTTP_HOST'], // тема письма
        $body, // текст письма
        'text/html'
    );


    $result['step']=2;
};

if($_REQUEST['step']>0){
    $result['step']=$result['step']+1;
    foreach($result['offers'] as $i=>$offer){
        $result['offers'][$i]['num']=(int)$_POST['num'][$offer['id']];
        E::set(array(
            'id'=>$offer['id'],
            'num'=>(int)$_POST['num'][$offer['id']]
        ));
    }
}

$result['summ']=0;

include_once(TEMPLATES_DIR.E::$template['path'].'/pages/'.$component.'/'.$template.'.php');
?>