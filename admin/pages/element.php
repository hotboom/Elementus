<?
$type_id=(int)$_GET['type'];
$type=E::getType($type_id);
$types=E::getFullType($type_id);
$type['fields']=E::getFullTypeFields($type);

$type['class']=E::getTypeClass($type['name']);
if(!class_exists($type['class']['name'])) require_once($root_path."/core/classes/".$type['name'].".php");

$act=htmlspecialchars($_GET['act']);
$element=array();
$connects=E::getConnectedTypes($type['id']);

if($act=='edit'|$act=='copy') {
    $element_id=(int)$_GET['elements'][0];
    $element=E::getById($element_id);
}
?>
<? if(!empty($_FILES)):
    $_FILES['file']['name']=substr(md5(time().rand(0,99)),0,20).'.'.substr($_FILES['file']['name'], strrpos($_FILES['file']['name'], '.') + 1);
    $uploaddir = $root_path.'/upload/files/';
    $uploadfile = $uploaddir . basename($_FILES['file']['name']);


    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) echo $_FILES['file']['name'];
    ?>
<? elseif(!empty($_POST['submit'])):
    //print_r($_POST);
    //E::debug();
    if($act=='delete') $result=$type['class']['name']::delete($_POST['elements']);
    else {
        $result=$type['class']['name']::set($_POST['fields']);
        if(!empty($_POST['connected'])) {
            foreach($_POST['connected'] as $connected) {
                if(!empty($connected['delete'])&&!empty($connected['id'])) $type['class']['name']::delete($connected['id']);
                $type['class']['name']::set($connected);
            }
        }
    }
    ?>
    <? if($result):?>
        <div class="alert alert-success"><?=t($type['name'].' succesfuly '.$act)?></div>
    <? else:?>
        <div class="alert alert-warning"><?=t('Error occurred:'.E::$error['desc'])?></div>
    <? endif;?>
    <script>
        $(window).hashchange();
        $(function() {
            $('.modal-title').html('<?=t($type['name'])?> <?=t('added')?> ');
            $('.modal-footer').show();
        });
    </script>
<? else:?>
    <script>
        $(function() {
            $('.modal-title').html('<?=t($act.' '.$type['name'],true)?>');
            $('.modal-footer').hide();
        });
    </script>
    <? if($act!='delete'):?>
    <? if(!empty($connects)):?>
    <ul id="element-nav" class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab"><?=t('Properties')?></a></li>
        <? foreach($connects as $connect):?>
            <li class=""><a href="#<?=$connect['type']['name']?>" data-toggle="tab"><?=t($connect['type']['name'],true)?></a></li>
        <? endforeach;?>
    </ul>
    <? endif; ?>
    <? endif;?>

    <form method="POST" data-async data-target="#window .modal-body" action="/admin/index.php?page=element&type=<?=$type['id']?>&act=<?=$act?>" class="form-horizontal">
        <div class="tab-content">
            <div class="tab-pane fade active in" id="home">
                <? if($act=='delete'):?>
                    <p><?=t('delete selected elements')?>?</p>
                    <? if(is_array($_GET['elements'])):?>
                    <? foreach($_GET['elements'] as $i=>$val):?>
                    <input type="hidden" name="elements[]" value="<?=$val?>">
                    <? endforeach;?>
                    <?endif?>
                    <button type="submit" class="btn btn-success"><?=t('Delete')?></button>
                    <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
                    <input type="hidden" name="submit" value="submit">
                <?else:?>
                    <fieldset>
                        <input name="fields[id]" type="hidden" value="<?=($act!='copy' ? $element['id']:'')?>">
                        <? foreach($type['fields'] as $i=>$field):
                            Template::render('pages/field_types/field_group_hor.php',array(
                                'field'=>$field,
                                'value'=>$element[$field['name']],
                                'name'=>'fields['.$field['name'].']')
                            );
                        endforeach;?>
                        <div class="form-group">
                            <label class="col-lg-2 control-label"></label>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-success"><?=t($act)?></button>
                                <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
                            </div>
                        </div>
                        <input type="hidden" name="fields[type]" value="<?=$type['id']?>">
                        <input type="hidden" name="submit" value="submit">
                    </fieldset>
                <?endif;?>
            </div>
            <? if($act!='delete'):?>
            <? foreach($connects as $connect):?>
                <div class="tab-pane fade" id="<?=$connect['type']['name']?>">
                    <?
                    //E::debug();
                    $cType=E::getType($connect['type']);
                    $cType['fields']=E::getTypeFields($cType);
                    $cElements=E::get(array('filter'=>"`".$connect['field']."`='".$element['element_id']."'",'type'=>$connect['type']));
                    if(empty($cElements)) $cElements['example']=array();
                    ?>
                    <p class="pull-left">
                        <a href="#add" class="btn btn-success" tabindex="1"><i class="fa fa-plus"></i> <?=t('Add')?></a>
                        <a href="#copy" class="btn btn-primary disabled" tabindex="3"><i class="fa fa-copy"></i> <?=t('Copy')?></a>
                        <a href="#delete" class="btn btn-danger" tabindex="4"><i class="fa fa-times"></i> <?=t('Delete')?></a>
                    </p>
                    <table id="connected" class="table table-hover table-condensed selectable">
                        <thead>
                        <tr>
                        <th class="col-lg-1"><a href="#/type/id/id/<?=$type['id']?>/sort/<?=$field['name']?>">#</a></th>
                        <? foreach($cType['fields'] as $i=>$field):?>
                            <? if($field['name']==$connect['field']) continue; ?>
                            <th><a href="#/type/id/id/<?=$type['id']?>/sort/<?=$field['name']?>"><?=t($field['name'])?></a></th>
                        <? endforeach; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <? foreach($cElements as $i=>$cElement): ?>
                            <tr<?=($i==='example' ? ' style="display:none;"' : ' data-element="1"')?>>
                                <td>
                                    <input type="checkbox" name="connected_elements[]" value="<?=$cElement['id']?>"> <span class="id"><?=$cElement['id']?></span>
                                    <input name="id" type="hidden" value="<?=$cElement['id']?>" data-field="id">
                                    <input name="<?=$connect['field']?>" class="common" type="hidden" value="<?=$element['id']?>" data-field="<?=$connect['field']?>">
                                    <input name="type" class="common" type="hidden" value="<?=$cType['name']?>" data-field="type">
                                </td>
                                <? foreach($cType['fields'] as $j=>$field):?>
                                    <? if($field['name']===$connect['field']) continue; ?>
                                    <td>
                                    <? if($field['type']==='elements'):?>
                                        <? Template::render('pages/field_types/elements.php',array('field'=>$field,'value'=>$cElement[$field['name']], 'name'=>$field['name'])); ?></td>
                                    <? elseif($field['type']==='varchar'|$field['type']==='int'):?>
                                        <input name="<?=$field['name']?>" class="form-control" type="text" value="<?=$cElement[$field['name']]?>" data-field="<?=$field['name']?>">
                                    <? endif; ?>
                                    </td>
                                <? endforeach; ?>
                            </tr>
                        <? endforeach; ?>
                        </tbody>
                    </table>
                    <script>
                        $(function(){
                            function changeConnectedFieldsNames(){
                                $("#connected tr[data-element='1']").find('input, select, textarea').attr('name',function( i, val ) {
                                    if(!$(this).attr('data-field')) return val;
                                    return 'connected['+$(this).parents('tr').index()+']['+$(this).attr('data-field')+']';
                                });
                            }
                            changeConnectedFieldsNames();

                            $("a[href='#add']").click(function(e){
                                var clone=$('#connected tr:eq(1)').clone();
                                $('#connected').append(clone);
                                clone.find("input[class!='common']").val('');
                                clone.find('.id').remove();
                                clone.attr('data-element',1);
                                changeConnectedFieldsNames();
                                clone.show();
                                e.preventDefault();
                            });
                            $("a[href='#copy']").click(function(e){
                                var clone=$('#connected tr:eq(1)').clone();
                                clone.find('.id').remove();
                                $('#connected').append(clone);
                                e.preventDefault();
                            });
                            $("a[href='#delete']").click(function(e){
                                $('#connected tr.active td:eq(0)').append('<input type="hidden" value="1" name="delete" data-field="delete">');
                                changeConnectedFieldsNames();
                                $('#connected tr.active').hide();
                                e.preventDefault();
                            });
                        });
                    </script>
                    <fieldset>
                    <p>
                        <button type="submit" class="btn btn-success"><?=t($act)?></button>
                        <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
                    </p>
                    </fieldset>
                </div>
            <? endforeach;?>
            <? endif; ?>

        </div>
    </form>
    <div class="clearfix"></div>
<? endif;?>