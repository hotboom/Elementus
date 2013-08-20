<? require_once("pages/header.php"); ?>
        <div class="row">
            <div class="col-lg-2">
                <h3><?=t('Types');?></h3>
                <!--<form class="form-horizontal">
                    <div class="form-group row">
                        <label for="typesShow" class="col-lg-4"><?=t('View')?></label>
                        <div class="col-lg-8">
                        <select name="typesShow" id="typesShow" class="form-control">
                            <option value="tree"><?=t('tree')?></option>
                            <option value="tree"><?=t('groups')?></option>
                        </select>
                        </div>
                    </div>
                </form>-->
                <?
                //Elements::debug();
                function treeTypes($parent_id='NULL',$class='types'){
                    if($parent_id!='NULL') $filter="parent='".$parent_id."'";
                    else $filter="parent is NULL";
                    $types=Elements::getTypes($filter);
                    if(empty($types)) return false;

                    if($parent_id==0) echo '<ul class="list-unstyled '.$class.'">';
                    else echo '<ul>';
                    foreach($types as $type){
                        echo '<li><a href="#/page/type/'.$type['id'].'">'.t($type['name'],true).'</a>';
                        treeTypes($type['id']);
                        echo '</li>';
                    }
                    echo '</ul>';
                    return true;
                }
                treeTypes();
                ?>
            </div>
            <div id="page" class="col-lg-10">
                loading...
            </div>
        </div>
<? require_once("pages/footer.php"); ?>