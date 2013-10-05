<?
if(!empty($_GET['act'])) $act=htmlspecialchars($_GET['act']);
else $act='add';

if($act=='edit'|$act=='copy') {
    $type_id=(int)$_GET['type'];
    $type=E::getType($type_id);
}
else $type=array();
?>

<? if(!empty($_POST['submit'])):
    //E::debug();

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
            <button type="submit" class="btn btn-success"><?=t($act)?></button>
            <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
            <input type="hidden" name="fields[type]" value="<?=$type['id']?>">
            <input type="hidden" name="submit" value="submit">
        </fieldset>
    <?endif;?>
    </form>
<? endif;?>