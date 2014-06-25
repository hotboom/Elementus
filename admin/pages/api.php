<?
if(!empty($_GET['method'])){
    $params=array();
    if(!empty($_GET['search'])) $params['filter']="header LIKE '%".$_GET['search']."%'";
    $params=array_merge($params,$_GET['params']);
    $res=E::$_GET['method']($params);
    echo json_encode($res);
}
?>