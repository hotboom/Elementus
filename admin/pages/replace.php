<?
$type_id=(int)$_GET['type'];
$type=E::getType($type_id);
$fields=E::getTypeFields($type);

if(!empty($_POST['submit'])):
    if($res=E::replace($type, $_POST['field'], $_POST['find'], $_POST['replace'])):
        echo t('Replace successful');
    endif;
else:
?>
<form method="POST" data-async data-target="#window .modal-body" action="/admin/index.php?page=replace&type=<?=$type['id']?>" class="form-horizontal">
    <fieldset>
        <div class="form-group">
            <label class="col-lg-2 control-label" for="input_type"><?=t('Field')?></label>
            <div class="col-lg-10">
                <select name="field" id="input_field" class="form-control selectpicker">
                    <? foreach($fields as $field):?>
                        <option value="<?=$field['name']?>"><?=$field['name']?></option>
                    <? endforeach;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label" for="input_type"><?=t('Find')?></label>
            <div class="col-lg-10">
                <input name="find" type="text" class="form-control" id="input_find" value="" placeholder="string to find">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label" for="input_type"><?=t('Replace')?></label>
            <div class="col-lg-10">
                <input name="replace" type="text" class="form-control" id="input_replace" value="" placeholder="string to replace">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label"></label>
            <div class="col-lg-10">
                <button type="submit" class="btn btn-success"><?=t('Replace')?></button>
                <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
            </div>
        </div>
        <input type="hidden" name="submit" value="submit">
    </fieldset>
</form>
<? endif; ?>