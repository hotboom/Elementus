<? require_once("pages/header.php"); ?>
            <div class="leftBar">
                <h3><?=t('Types');?></h3>
                <?
                //E::debug();
                function treeTypes($parent_id='NULL',$class='types'){
                    if($parent_id!='NULL') $filter="parent='".$parent_id."'";
                    else $filter="parent is NULL";
                    $types=E::getTypes($filter);
                    if(empty($types)) return false;

                    if($parent_id==0) echo '<ul class="list-unstyled '.$class.'">';
                    else echo '<ul>';
                    foreach($types as $type){
                        echo '<li><a href="#/type/id/'.$type['id'].'">'.t($type['name'],true).'</a>';
                        treeTypes($type['id']);
                        echo '</li>';
                    }
                    echo '</ul>';
                    return true;
                }
                treeTypes();
                ?>
            </div>
            <div id="page" class="container mainBar">
                <?=t('Loading').'...'?>
            </div>
<? require_once("pages/footer.php"); ?>