<?
if(!empty($_GET['act'])) $act=htmlspecialchars($_GET['act']);
else $act='add';

$type=Elements::getType((int)$_GET['type']);

if($act=='edit'|$act=='copy') {
    $field=array();
}
else $field=array();
?>

<? if(!empty($_POST['submit'])):
    echo '<pre>'.print_r($_POST['fields']).'</pre>';
    //echo '<pre>'.print_r($_GET['fields']).'</pre>';
    Elements::debug();

    if($act=='delete') $result=Elements::deleteTypeField((int)$_GET['type'],$_POST['fields']);
    else $result=Elements::setTypeField((int)$_GET['type'],$_POST['field']);

    if($act=='add') $done='added';
    if($act=='delete') $done='deleted';
    if($act=='edit') $done='edited';
    ?>
    <? if($result):?>
    <i class="icon-ok"></i> <?=t('Field succesfuly '.$done)?>
    <? else:?>
    <i class="icon-warning-sign"></i> <?=t('Error occurred:'.Elements::$error['desc'])?>
    <? endif;?>
    <script>
        $(function() {
            $('.modal-title').html('<?=t($type['name'].' added')?>');
            $('.modal-footer').show();
        });
        $.fn.deepLink('/admin/page/type_fields/<?=$type['id']?>');
    </script>
<? else:?>
    <script>
        $(function() {
            $('.modal-title').html('<?=t($act.' field')?>');
            $('.modal-footer').hide();
        });
    </script>
    <form method="POST" data-async data-target="#window .modal-body" action="/admin/router.php?page=field_form&type=<?=$type['id']?>&act=<?=$act?>">
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
    <script>
        $(function() {
            $('form[data-async]').submit(function(event) {
                var form = $(this);
                var target = $(form.attr('data-target'));
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),

                    success: function(data, status) {
                        target.html(data);
                    }
                });
                event.preventDefault();
            });
        });
    </script>
<? endif;?>