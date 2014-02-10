<?
$result['step']=1;

//Добавление товара в корзину
if(!empty($_GET['add'])){
    $offer_id=(int)$_GET['add'];

    //Проверка существования товара
    if(!$offer=E::getById($offer_id)) {
        header('HTTP/1.0 403 Forbidden');
        exit;
    }
    if($offer['type_id']!=20) {
        header('HTTP/1.0 403 Forbidden');
        exit;
    }

    //Создаем заказ (если уже не создан)
    $orders=E::get(array('type'=>22,'filter'=>array('hash'=>U::$user['hash'],'status'=>'not send')));

    if(empty($orders)){
        $order_id=E::set(array(
            'type'=>22,
            'date'=>date('Y-m-d H:i'),
            'hash'=>U::$user['hash']
        ));
    }
    else $order_id=$orders[0]['id'];

    //Добавляем позицию в заказ если ее нет
    $added=E::get(array('type'=>23,'filter'=>array('offer'=>$offer_id,'order'=>$order_id)));
    if(empty($added)){
        E::set(array(
            'type'=>23,
            'order'=>$order_id,
            'offer'=>$offer_id
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

//Обновление заказа
if($_REQUEST['step']==2){
    //Сохранение заказа
    E::set(array(
        'id'=>$result['order']['id'],
        'paymethod'=>$_POST['paymethod'],
        'delivery'=>$_POST['delivery'],
        'status'=>'sended'
    ));

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

    //Сохранение адреса
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

    //Уведомление покупателю
    send_mime_mail(
        $_SERVER['HTTP_HOST'], // имя отправителя
        'no-reply@'.$_SERVER['HTTP_HOST'], // email отправителя
        $_POST['user']['name'], // имя получателя
        $_POST['user']['email'], // email получателя
        'utf-8', // кодировка переданных данных
        'utf-8', // кодировка письма
        'Заказ на '.$_SERVER['HTTP_HOST'], // тема письма
        $body // текст письма
    );

    //Уведомление админам
    $users=E::get(array(
        'type'=>'users',
        'filter'=>array('group_id'=>19)
    ));
    foreach($result['offers'] as $i=>$offer) {
        $product=E::getById($offer['offer']);
        if(is_array($product)) $result['offers'][$i]=array_merge($product,$offer);
    }

    $body='<h2><a href="http://'.$_SERVER['HTTP_HOST'].'/admin/#/type/id/4/filter[id]/'.U::$user['id'].'">Заказчик</a></h2>';
    $body.='имя: '.htmlspecialchars($_POST['user']['name']).'<br>';
    $body.='e-mail: '.htmlspecialchars($_POST['user']['email']).'<br>';
    $body.='телефон: '.htmlspecialchars($_POST['user']['phone']).'<br>';
    $body.='доставка: '.htmlspecialchars($_POST['delivery']).'<br>';
    $body.='оплата: '.htmlspecialchars($_POST['paymethod']).'<br>';
    $body.='<h2><a href="http://'.$_SERVER['HTTP_HOST'].'/admin/#/type/id/22/filter[id]/'.$result['order']['id'].'">Заказ</a></h2>';
    $body.='<table><tr><th>Наименование</th><th>Кол-во</th><th>Цена</th></tr>';
    foreach($result['offers'] as $offer) $body.='<tr><td>'.$offer['name'].'</td><td>'.$offer['num'].'</td><td>'.$offer['price'].'</td></tr>';
    $body.='</table>';

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

    $result['step']=2;
};

if($_REQUEST['step']>0){
    $result['step']=$result['step']+1;
    foreach($result['offers'] as $offer){
        E::set(array(
           'id'=>$offer['id'],
           'num'=>(int)$_POST['num'][$offer['id']]
        ));
    }
    $result['offers']=E::get(array('type'=>23,'filter'=>array('order'=>$result['order']['id'])));
}

$result['summ']=0;

foreach($result['offers'] as $i=>$offer) {
    $product=E::getById($offer['offer']);
    if(is_array($product)) $result['offers'][$i]=array_merge($product,$offer);
}

include_once(TEMPLATES_DIR.E::$template['path'].'/pages/'.$component.'/'.$template.'.php');
?>