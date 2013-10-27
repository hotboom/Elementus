<?
//print_r($_POST);
//E::debug();
if(!empty($_GET['act'])) $act=htmlspecialchars($_GET['act']);
else $act='add';

if($act=='edit'|$act=='copy') {
    $type_id=(int)$_GET['type'];
    $type=E::getType($type_id);
    $type['view']=E::getTypeOpt($type['id'],'view');
    $type['fields']=E::getTypeFields($type);
    if(empty($type['view'])) $type['view']=array('view'=>'','fields'=>array());
}
else $type=array();
?>

<? if(!empty($_POST['submit'])):
    //E::debug();
    print_r($_POST);
    if($act=='delete') $result=E::deleteType($_POST['types']);
    else $result=E::setType($_POST['type']);

    if($result):?>
        <div class="alert alert-success"><?=t($type['name'].' succesfuly '.$act)?></div>
    <? else:?>
        <div class="alert alert-warning"><?=t('Error occurred:'.E::$error['desc'])?></div>
    <? endif;?>
    <script>
        $(window).hashchange();
        $(function() {
            $('.modal-title').html('<?=t($type['name'])?> <?=t($act)?> ');
            $('.modal-footer').show();
        });
    </script>
<? else:?>
    <script>
        $(window).hashchange();
        $(function() {
            $('.modal-title').html('<?=t($act)?> <?=t('type')?>');
            $('.modal-footer').hide();
        });
    </script>
    <form method="POST" data-async data-target="#window .modal-body" action="/admin/index.php?page=type_form&type=<?=$type['id']?>&act=<?=$act?>">
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
            <input name="type[id]" type="hidden" value="<?=($act!='copy' ? $type['id']:'')?>">
            <div class="form-group">
                <label for="input_parent"><?=t('Parent')?></label>
                <? $allTypes=E::getTypes(); ?>
                <select name="type[parent]" id="input_parent" class="form-control">
                    <option value=""><?=t('Root')?></option>
                    <? foreach($allTypes as $t):?>
                        <option value="<?=$t['id']?>" <?=($type['parent']==$t['id'] ? 'selected ' : '')?>><?=t($t['name'],true)?></option>
                    <?endforeach;?>
                </select>
            </div>
            <div class="form-group">
                <label for="input_parent"><?=t('Group')?></label>
                <? $groups=E::getTypeGroups(); ?>
                <select name="type[group]" id="input_parent" class="form-control">
                    <option value=""><?=t('Root')?></option>
                    <? foreach($groups as $g):?>
                        <option value="<?=$g['id']?>" <?=($type['group']==$g['id'] ? 'selected ' : '')?>><?=t($g['name'],true)?></option>
                    <?endforeach;?>
                </select>
            </div>
            <div class="form-group">
                <label for="input_name"><?=t('Name')?></label>
                <input name="type[name]" type="text" class="form-control" id="input_name" value="<?=$type['name']?>">
            </div>
            <div id="advanced" style="display:none;">
                <div class="form-group">
                    <label for="input_view"><?=t('Show fields in list')?></label>
                    <select name="type[view][type]" id="input_view" class="form-control selectpicker">
                        <option value=""><?=t('All')?></option>
                        <option value="except" <?=($type['view']['type']=='except' ? 'selected' : '')?>><?=t('Except defined')?></option>
                        <option value="only" <?=($type['view']['type']=='only' ? 'selected' : '')?>><?=t('Only defined')?></option>
                    </select>
                </div>
                <div class="form-group" id="group_view_fields" <?=(empty($type['view']['type']) ? 'style="display:none;"' : '')?>>
                    <label for="input_view_fields"><?=t('Fields')?></label>
                    <select name="type[view][fields][]" class="selectpicker form-control" multiple title="<?=t('Choose fields...')?>">
                        <? foreach($type['fields'] as $field):?><option value="<?=$field['name']?>"<?=(in_array($field['name'],$type['view']['fields']) ? ' selected' : '')?>><?=$field['name']?></option><?endforeach;?>
                    </select>
                </div>
            </div>
            <script>
                $('#input_view').change(function(){
                    var group_view_fields=$('#group_view_fields');
                    if($(this).val()) group_view_fields.show();
                    else group_view_fields.hide();

                });
            </script>
            <button type="submit" class="btn btn-success"><?=t($act)?></button>
            <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
            <a href="#" class="btn btn-default pull-right" onClick="$('#advanced').toggle(); $(this).find('i').toggleClass('fa-angle-down').toggleClass('fa-angle-up'); return false;"><i class="fa fa-angle-down"></i> <?=t('Advanced settings')?></a>
            <input type="hidden" name="submit" value="submit">
        </fieldset>
    <?endif;?>
    </form>
<? endif;?>