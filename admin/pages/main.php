<? require_once("../core/init.php"); ?>
<? require_once("pages/header.php"); ?>
    <div class="container">
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
            </div>
            <div id="page" class="col-lg-10">
                loading...
            </div>
        </div>
    </div><!-- /.container -->
<? require_once("pages/footer.php"); ?>