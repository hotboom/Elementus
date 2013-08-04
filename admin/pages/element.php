<? require_once("../core/init.php"); ?>
<?
//set headers to NOT cache a page
header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$type_id=(int)$_GET['type'];
$type=Elements::getTypeById($type_id);
$types=Elements::getFullType($type_id);
$type['fields']=array();
//Elements::debug();
foreach($types as $i=>$val){
    $types[$i]['fields']=Elements::getTypeFields($types[$i]);
    $type['fields']=array_merge($type['fields'],$types[$i]['fields']);
}
$type['class']=Elements::getTypeClass($type['name']);

if(!class_exists($type['class']['name'])) require_once($root_path."/core/classes/".$type['name'].".php");

$act=htmlspecialchars($_GET['act']);
?>

<? if(!empty($_POST['submit'])):
    //Elements::debug();
    $type['class']['name']::set($_POST['fields']);
    if($act=='add') $done='added';
    if($act=='delete') $done='deleted';
    if($act=='edit') $done='edited';
?>
<span class="glyphicon glyphicon-ok"></span> <?=t($type['name']).' '.t('succesfuly').' '.t($done)?>
<pre><? print_r($_POST); ?></pre>
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
        $('.modal-title').html('<?=t($act)?> <?=t($type['name'])?>');
        $('.modal-footer').hide();
    });
</script>
<? if($act=='delete'):?>
    <form>
        <p><?=t('delete selected elements')?>?</p>
        <a href="#" class="btn btn-danger" data-dismiss="modal"><?=t('Delete')?></a>
        <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
    </form>
<?else:?>
<form method="POST" data-async data-target="#window .modal-body" action="/admin/router.php?page=element&type=<?=$type['id']?>&act=<?=$act?>">
    <fieldset>
            <? foreach($type['fields'] as $i=>$field):?>
            <? if($field['Field']=='element_id'):?>
                <input name="fields[<?=$field['Field']?>]" type="hidden" value="">
            <? elseif(!empty($field['FK'])): ?>
                <?
                $fk_type=Elements::getTypeByName($field['FK']);
                $fk_type['class']=Elements::getTypeClass($fk_type['name']);

                if($fk_type['class']['name']::$foreign_select=='select'):
                $fk_elements=$fk_type['class']['name']::get();
                ?>
                <div class="form-group">
                    <label for="input<?=$field['Field']?>"><?=t($field['Field'])?></label>
                    <select name="fields[<?=$field['Field']?>]" id="input<?=$field['Field']?>" class="form-control">
                        <option value="NULL"><?=t('Root')?></option>
                        <? foreach($fk_elements as $fk_element):?>
                        <option value="<?=$fk_element['id']?>"><?=$fk_element['name']?></option>
                        <? endforeach;?>
                    </select>
                </div>
                <? endif;?>
            <? elseif($field['Type']=='text'): ?>
                <div class="form-group">
                    <label for="input<?=$field['Field']?>"><?=t($field['Field'])?></label>
                    <textarea name="fields[<?=$field['Field']?>]" class="form-control" rows="3"></textarea>
                </div>
            <? else:?>
                <div class="form-group">
                    <label for="input<?=$field['Field']?>"><?=t($field['Field'])?></label>
                    <input name="fields[<?=$field['Field']?>]" type="text" class="form-control" id="input<?=$field['Field']?>" placeholder="<?=t($field['Field'])?>">
                </div>
            <? endif;?>
            <? endforeach;?>
            <button type="submit" class="btn btn-success"><?=t($act)?></button>
            <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
            <input type="hidden" name="submit" value="submit">
    </fieldset>
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
<?endif;?>
<? endif;?>