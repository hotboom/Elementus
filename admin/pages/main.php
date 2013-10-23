<? require_once("pages/header.php"); ?>
            <div class="leftBar">
                <h3><?=t('Types');?></h3>
                <div class="panel-group" id="accordion">
                    <? //E::debug();
                    $groups=E::getTypeGroups();
                    foreach($groups as $i=>$group):?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$i?>">
                                    <?=t($group['name'])?> <i class="fa fa-angle-<?=(!$i ? 'up' : 'down')?>"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse<?=$i?>" class="panel-collapse collapse<?=(!$i ? ' in' : '')?>">
                            <div class="panel-body">
                                <? treeTypes(array('parent'=>false,'group'=>$group['id'], 'class'=>'types'));?>
                            </div>
                        </div>
                    </div>
                    <? endforeach;?>
                </div>
                <script>
                    $('.collapse').on('hidden.bs.collapse', function () {
                        $(this).parent().find('.fa.fa-angle-up').attr('class','fa fa-angle-down');
                    });
                    $('.collapse').on('shown.bs.collapse', function () {
                        $(this).parent().find('.fa.fa-angle-down').attr('class','fa fa-angle-up');
                    });
                </script>

                <?
                function treeTypes($params=array('parent'=>false,'group'=>false,'class'=>'types')){
                    $filter='';
                    if(!empty($params['group'])) $filter.="`group`='".$params['group']."' AND ";
                    if($params['parent']) $filter.="`parent`='".$params['parent']."'";
                    else $filter.="parent is NULL";
                    $types=E::getTypes($filter);
                    if(empty($types)) return false;

                    if(!$params['parent']) echo '<ul class="list-unstyled '.$params['class'].'">';
                    else echo '<ul>';
                    foreach($types as $type){
                        echo '<li><a href="#/type/id/'.$type['id'].'">'.t($type['name'],true).'</a>';
                        $params['parent']=$type['id'];
                        treeTypes($params);
                        echo '</li>';
                    }
                    echo '</ul>';
                    return true;
                }
                ?>
            </div>
            <div id="page" class="container mainBar">
                <?=t('Loading').'...'?>
            </div>
<? require_once("pages/footer.php"); ?>