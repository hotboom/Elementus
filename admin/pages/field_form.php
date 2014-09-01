<?
if(!empty($_GET['act'])) $act=htmlspecialchars($_GET['act']);
else $act='add';

$type=E::getType((int)$_GET['type']);
$type['table']=E::getTypeTableName($type);
$type['fields']=E::getTypeFields($type);
if($act=='edit'|$act=='copy') {
    $field=E::getField($type,$_GET['fields'][0]);
}
else $field=array();
?>

<? if(!empty($_POST['submit'])):
    if($act=='delete') $result=E::deleteTypeField((int)$_GET['type'],$_POST['fields']);
    else $result=E::setField((int)$_GET['type'],$_POST['field']);

    ?>
    <? if($result):?>
    <i class="fa fa-ok"></i> <?=t('Field succesfuly '.$act)?>
    <? else:?>
    <? foreach(E::$errors as $error):?>
    <i class="fa fa-warning-sign"></i> <?=t('Error occurred:'.E::$error['desc'])?>
    <? endforeach;?>
    <? endif;?>
    <script>
        $(window).hashchange();
        $(function() {
            $('.modal-title').html('<?=t('Field '.$act)?>');
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
    <form method="POST" data-async data-target="#window .modal-body" action="/admin/index.php?page=field_form&type=<?=$type['id']?>&act=<?=$act?>" class="form-horizontal">
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
            <input name="field[old_name]" type="hidden" value="<?=$field['name']?>">
            <div class="form-group">
                <label class="col-lg-2 control-label" for="input_name"><?=t('Name')?></label>
                <div class="col-lg-10">
                    <input name="field[name]" type="text" class="form-control" id="input_name" value="<?=$field['name']?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label" for="input_type"><?=t('Type')?></label>
                <?
                $ftypes=array(
                    'varchar'=>'String',
                    'int'=>'Integer',
                    'decimal'=>'Decimal',
                    'text'=>'Text',
                    'elements'=>'Elements select',
                    'enum'=>'Values select',
                    'html'=>'HTML',
                    'file'=>'File',
                    'image'=>'Image',
                    'date'=>'Date',
                    'datetime'=>'Date and time'
                );
                ?>
                <div class="col-lg-10">
                    <select name="field[type]" id="input_type" class="form-control">
                        <? foreach($ftypes as $i=>$ftype):?>
                            <option value="<?=$i?>"<?=($field['type']==$i ? ' selected="selected"' : '')?>><?=t($ftype)?></option>
                        <? endforeach;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                        <label><input name="field[multiple]" type="checkbox"<?=($field['multiple'] ? ' checked="checked"' : '')?>> <?=t('multiple')?></label>
                    </div>
                </div>
            </div>
            <div class="form-group extra" id="extra_enum" style="<?=($field['type']!='enum' ? 'display:none;' : '')?>">
                <label class="col-lg-2 control-label" for="input_type"><?=t('List entries')?></label>
                <div class="col-lg-10">
                    <? if(!empty($field['values'])):?>
                        <? foreach($field['values'] as $val):?>
                            <input name="field[enum][list][]" type="text" class="form-control select_value" value="<?=$val?>">
                        <? endforeach;?>
                    <? else:?>
                        <input name="field[enum][list][]" type="text" class="form-control select_value" value="">
                    <? endif;?>
                    <a href="#" class="btn btn-default" id="add_select_value"><i class="fa fa-plus"></i> <?=t('More')?></a>
                </div>
            </div>
            <div class="form-group extra" id="extra_elements" style="<?=($field['type']!='elements' ? 'display:none;' : '')?>">
                <label class="col-lg-2 control-label" for="input_type"><?=t('Type')?></label>
                <? $types=E::getTypes();?>
                <div class="col-lg-10">
                    <select name="field[elements_type]" id="input_type" class="form-control">
                        <? foreach($types as $i=>$t):?>
                            <option value="<?=$t['id']?>"<?=($field['elements_type']==$t['name'] ? ' selected="selected"' : '')?>><?=t($t['name'])?></option>
                        <? endforeach;?>
                    </select>
                </div>
            </div>
            <div id="advanced" style="display:none;">
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="input_name"><?=t('Translate')?></label>
                    <div class="col-lg-10">
                        <input name="field[lang]" type="text" class="form-control" id="input_name" value="<?=t($field['name'])?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="input_default"><?=t('Default')?></label>
                    <div class="col-lg-10">
                        <input name="field[default]" type="text" class="form-control" id="input_default" value="<?=$field['default']?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="input_hide"><?=t('Hide in list')?></label>
                    <div class="col-lg-10">
                        <select name="field[hide]" id="input_hide" class="form-control">
                            <option value="0"><?=t('no')?></option>
                            <option value="1"<?=($field['hide'] ? ' selected="selected"' : '')?>><?=t('yes')?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="input_default"><?=t('Placeholder')?></label>
                    <div class="col-lg-10">
                        <input name="field[placeholder]" type="text" class="form-control" id="input_default" value="<?=$field['placeholder']?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="input_default"><?=t('After')?></label>
                    <div class="col-lg-10">
                        <select name="field[after]" id="input_type" class="form-control">
                            <option value=""><?=t('at the end')?></option>
                            <? foreach($type['fields'] as $i=>$v):?>
                                <option value="<?=$v['name']?>"<?=($field['after']==$v ? ' selected="selected"' : '')?>><?=$v['name']?></option>
                            <? endforeach;?>
                        </select>
                    </div>
                </div>
            </div>
            <script>
                $('#input_type').change(function(){
                    $('.extra').hide();
                    $('#extra_'+$(this).val()).show();
                });

                $('#add_select_value').click(function(e){
                    $('.select_value:last').after($('.select_value:last').clone());
                    $('.select_value:last').val('');
                    e.preventDefault();
                });
            </script>
            <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <div class="col-lg-10">
                    <button type="submit" class="btn btn-success"><?=t($act)?></button>
                    <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
                    <a href="#" class="btn btn-default pull-right" onClick="$('#advanced').toggle(); return false;"><?=t('Advanced settings')?></a>
                </div>
            </div>
            <input type="hidden" name="fields[type]" value="<?=$type['id']?>">
            <input type="hidden" name="submit" value="submit">
        </fieldset>
    <?endif;?>
    </form>
<? endif;?>