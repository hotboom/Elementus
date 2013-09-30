<?
if(!empty($_GET['act'])) $act=htmlspecialchars($_GET['act']);
else $act='add';

$type=E::getType((int)$_GET['type']);
$type['table']=E::getTypeTableName($type);
if($act=='edit'|$act=='copy') {
    $field=E::getField($type,$_GET['fields'][0]);
}
else $field=array();
?>

<? if(!empty($_POST['submit'])):
    //E::debug();

    if($act=='delete') $result=E::deleteTypeField((int)$_GET['type'],$_POST['fields']);
    else $result=E::setField((int)$_GET['type'],$_POST['field']);

    ?>
    <? if($result):?>
    <i class="icon-ok"></i> <?=t('Field succesfuly '.$act)?>
    <? else:?>
    <i class="icon-warning-sign"></i> <?=t('Error occurred:'.E::$error['desc'])?>
    <? endif;?>
    <script>
        $(window).hashchange();
        $(function() {
            $('.modal-title').html('<?=t($type['name'].' added')?>');
            $('.modal-footer').show();
        });
    </script>
<? else:?>
    <script>
        $(function() {
            $('.modal-title').html('<?=t($act.' field')?>');
            $('.modal-footer').hide();
        });
    </script>
    <form method="POST" data-async data-target="#window .modal-body" action="/admin/index.php?page=field_form&type=<?=$type['id']?>&act=<?=$act?>">
    <? if($act=='delete'):?>
        <p><?=t('delete selected fields')?>?</p>
        <? if(is_array($_GET['fields'])):?>
            <? foreach($_GET['fields'] as $i=>$val):?>
                <input type="hidden" name="fields[]" value="<?=$val?>">
            <? endforeach;?>
        <?endif?>
        <button type="submit" class="btn btn-success"><?=t('Delete')?></button>
        <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
        <input type="hidden" name="submit" value="submit">
    <?else:?>
        <fieldset>
            <input name="field[act]" type="hidden" value="<?=$act?>">
            <div class="form-group">
                <label for="input_name"><?=t('Name')?></label>
                <input name="field[name]" type="text" class="form-control" id="input_name" value="<?=$field['name']?>">
            </div>
            <div class="form-group">
                <label for="input_type"><?=t('Type')?></label>
                <?
                $ftypes=array(
                    'int'=>'Integer',
                    'string'=>'String',
                    'text'=>'Text',
                    'select'=>'Select',
                    'html'=>'HTML',
                    'file'=>'File',
                    'image'=>'Image'
                );
                ?>
                <select name="field[type]" id="input_type" class="form-control">
                    <? foreach($ftypes as $i=>$ftype):?>
                    <option value="<?=$i?>"<?=($field['type']==$i ? ' selected="selected"' : '')?>><?=t($ftype)?></option>
                    <? endforeach;?>
                </select>
                <script>
                    $('#input_type').change(function(event){
                        alert('test');
                    });
                </script>
            </div>
            <button type="submit" class="btn btn-success"><?=t($act)?></button>
            <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
            <input type="hidden" name="fields[type]" value="<?=$type['id']?>">
            <input type="hidden" name="submit" value="submit">
        </fieldset>
    <?endif;?>
    </form>
<? endif;?>