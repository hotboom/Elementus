<?
if(!empty($_GET['act'])) $act=htmlspecialchars($_GET['act']);
else $act='add';

$type=E::getType((int)$_GET['type']);

if($act=='edit'|$act=='copy') {
    $field=array();
}
else $field=array();
?>

<? if(!empty($_POST['submit'])):
    echo '<pre>'.print_r($_POST['fields']).'</pre>';
    //echo '<pre>'.print_r($_GET['fields']).'</pre>';
    E::debug();

    if($act=='delete') $result=E::deleteTypeField((int)$_GET['type'],$_POST['fields']);
    else $result=E::setTypeField((int)$_GET['type'],$_POST['field']);

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
            <input name="field[field]" type="hidden" value="<?=($act!='copy' ? $field['Field']:'')?>">
            <div class="form-group">
                <label for="input_name"><?=t('Name')?></label>
                <input name="field[name]" type="text" class="form-control" id="input_name" value="<?=$field['Field']?>">
            </div>
            <div class="form-group">
                <label for="input_type"><?=t('Type')?></label>
                <select name="field[type]" id="input_type" class="form-control">
                    <option value="INT(11)"><?=t('Int')?></option>
                    <option value="VARCHAR(255)"><?=t('Varchar')?></option>
                    <option value="TEXT"><?=t('Text')?></option>
                    <option value="FOREIGN KEY"><?=t('Foreign key')?></option>
                </select>
            </div>
            <button type="submit" class="btn btn-success"><?=t($act)?></button>
            <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
            <input type="hidden" name="fields[type]" value="<?=$type['id']?>">
            <input type="hidden" name="submit" value="submit">
        </fieldset>
    <?endif;?>
    </form>
<? endif;?>