<? require_once("../core/init.php"); ?>
<h1>Dashboard</h1>
<?
function treeTypes($parent_id=0,$class='types'){
    $types=Elements::getTypes(array('parent'=>$parent_id));
    if(empty($types)) return false;

    if($parent_id==0) echo '<ul class="'.$class.'">';
    else echo '<ul>';
    foreach($types as $type){
        echo '<li><a href="#/page/type/'.$type['id'].'">'.t($type['name']).'</a>';
        treeTypes($type['id']);
        echo '</li>';
    }
    echo '</ul>';
    return true;
}
treeTypes(0);
?>