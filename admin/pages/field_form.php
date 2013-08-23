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
    //echo '<pre>'.print_r($_POST['fields']).'</pre>';
    //Elements::debug();

    if($act=='delete') Elements::deleteTypeField((int)$_POST['type'],$_POST['field']);
    else Elements::setTypeField((int)$_POST['type'],$_POST['field']);

    if($act=='add') $done='added';
    if($act=='delete') $done='deleted';
    if($act=='edit') $done='edited';
    ?>
    <i class="icon-ok"></i> <?=t($type['name']).' '.t('succesfuly').' '.t($done)?>
    <script>
        $(function() {
            $('.modal-title').html('<?=t($type['name'])?> <?=t('added')?> ');
            $('.modal-footer').show();
            $.deepLink('/admin/page/type/<?=$type['id']?>');
        });
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
            <input name="type[id]" type="hidden" value="<?=($act!='copy' ? $type['id']:'')?>">
            <div class="form-group">
                <label for="input_name"><?=t('Name')?></label>
                <input name="field[name]" type="text" class="form-control" id="input_name" value="<?=$field['name']?>">
            </div>
            <div class="form-group">
                <label for="input_type"><?=t('Parent')?></label>
                <select name="field[type]" id="input_type" class="form-control">
                    <option value="INT(11)">Int</option>
                    <option value="VARCHAR(255)">Varchar</option>
                    <option value="TEXT">Text</option>
                    <option value="FOREIGN KEY">Foreign key</option>
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