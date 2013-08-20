<?
if(!empty($_GET['act'])) $act=htmlspecialchars($_GET['act']);
else $act='add';

if($act=='edit'|$act=='copy') {
    $type_id=(int)$_GET['type'];
    $type=Elements::getType($type_id);
}
else $type=array();
?>

<? if(!empty($_POST['submit'])):
    //echo '<pre>'.print_r($_POST['fields']).'</pre>';
    //Elements::debug();

    if($act=='delete') $type['class']['name']::delete($_POST['elements']);
    else $type['class']['name']::set($_POST['fields']);

    if($act=='add') $done='added';
    if($act=='delete') $done='deleted';
    if($act=='edit') $done='edited';
    ?>
    <span class="glyphicon glyphicon-ok"></span> <?=t($type['name']).' '.t('succesfuly').' '.t($done)?>
    <script>
        $(function() {
            $('.modal-title').html('<?=t($type['name'])?> <?=t('added')?> ');
            $('.modal-footer').show();
            console.log($.deepLink);
            $.deepLink('/admin/page/type/<?=$type['id']?>');
        });
    </script>
<? else:?>
    <script>
        $(function() {
            $('.modal-title').html('<?=t($act)?> <?=t('type')?>');
            $('.modal-footer').hide();
        });
    </script>
    <? if($act=='delete'):?>
    <form method="POST" data-async data-target="#window .modal-body" action="/admin/router.php?page=element&type=<?=$type['id']?>&act=<?=$act?>">
        <p><?=t('delete selected elements')?>?</p>
        <? if(is_array($_GET['elements'])):?>
            <? foreach($_GET['elements'] as $i=>$val):?>
                <input type="hidden" name="elements[]" value="<?=$val?>">
            <? endforeach;?>
        <?endif?>
        <button type="submit" class="btn btn-success"><?=t('Delete')?></button>
        <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
        <input type="hidden" name="submit" value="submit">
    </form>
<?else:?>
    <form method="POST" data-async data-target="#window .modal-body" action="/admin/router.php?page=element&type=<?=$type['id']?>&act=<?=$act?>">
        <fieldset>
                    <input name="type[id]" type="hidden" value="<?=($act!='copy' ? $type['id']:'')?>">

                    <div class="form-group">
                        <label for="input_name"><?=t('Name')?></label>
                        <input name="type[name]" type="text" class="form-control" id="input_name" value="<?=$type['name']?>">
                    </div>

            <button type="submit" class="btn btn-success"><?=t($act)?></button>
            <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
            <input type="hidden" name="fields[type]" value="<?=$type['id']?>">
            <input type="hidden" name="submit" value="submit">
        </fieldset>
    </form>
<?endif;?>
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