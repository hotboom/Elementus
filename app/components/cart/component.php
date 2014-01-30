<?
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
    $order=E::get(array('type'=>22,'filter'=>array('hash'=>U::$user['hash'])));
    if(empty($order)){
        $order_id=E::set(array(
            'type'=>22,
            'hash'=>U::$user['hash']
        ));
    }
    else $order_id=$order[0]['id'];

    //Добавляем позицию в заказ если ее нет
    //E::debug();
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

$orders=E::get(array('type'=>22,'filter'=>array('hash'=>U::$user['hash'])));
$result['order']=$orders[0];

$result['offers']=E::get(array('type'=>23,'filter'=>array('order'=>$result['order']['id'])));

$result['summ']=0;
foreach($result['offers'] as $i=>$offer) {
    $product=E::getById($offer['offer']);
    if(is_array($product)) $result['offers'][$i]=array_merge($product,$offer);
}

include_once(TEMPLATES_DIR.E::$template['path'].'/pages/'.$component.'/'.$template.'.php');
?>