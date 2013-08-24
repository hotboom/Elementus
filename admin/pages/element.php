<?
$type_id=(int)$_GET['type'];
//Elements::debug();
$type=Elements::getType($type_id);
$types=Elements::getFullType($type_id);
$type['fields']=Elements::getFullTypeFields($type);

$type['class']=Elements::getTypeClass($type['name']);
if(!class_exists($type['class']['name'])) require_once($root_path."/core/classes/".$type['name'].".php");

$act=htmlspecialchars($_GET['act']);
$element=array();
if($act=='edit'|$act=='copy') {
    $element_id=(int)$_GET['elements'][0];
    //Elements::debug();
    $element=Elements::getById($element_id);
    //print_r($element);
}
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
            $.fn.deepLink('/admin/page/type/<?=$type['id']?>');
        });
    </script>
<? else:?>
    <script>
        $(function() {
            $('.modal-title').html('<?=t($act)?> <?=t($type['name'])?>');
            $('.modal-footer').hide();
        });
    </script>
    <form method="POST" data-async data-target="#window .modal-body" action="/admin/router.php?page=element&type=<?=$type['id']?>&act=<?=$act?>">
    <? if($act=='delete'):?>
        <p><?=t('delete selected elements')?>?</p>
        <? if(is_array($_GET['elements'])):?>
        <? foreach($_GET['elements'] as $i=>$val):?>
        <input type="test" name="elements[]" value="<?=$val?>">
        <? endforeach;?>
        <?endif?>
        <button type="submit" class="btn btn-success"><?=t('Delete')?></button>
        <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
        <input type="hidden" name="submit" value="submit">
    <?else:?>
        <fieldset>
            <input name="fields[id]" type="hidden" value="<?=($act!='copy' ? $element['element_id']:'')?>">
            <? foreach($type['fields'] as $i=>$field):?>
            <? if(!empty($field['FK'])): ?>
                <?
                $fk_type=Elements::getTypeByName($field['FK']);
                $fk_type['class']=Elements::getTypeClass($fk_type['name']);

                if($fk_type['class']['name']::$foreign_select=='select'):
                    $fk_elements=Elements::get(array('type'=>$fk_type['id']));
                    ?>
                    <div class="form-group">
                        <label for="input<?=$field['Field']?>"><?=t($field['Field'])?></label>
                        <select name="fields[<?=$field['Field']?>]" id="input<?=$field['Field']?>" class="form-control">
                            <? if($field['Null']=='YES'):?><option value="NULL"><?=t('not set')?></option><? endif;?>
                            <? foreach($fk_elements as $fk_element):?>
                            <option value="<?=$fk_element['id']?>" <?=($fk_element['id']==$element[$field['Field']] ? 'selected':'')?>><?=$fk_element['name']?></option>
                            <? endforeach;?>
                        </select>
                    </div>
                <? endif; ?>
            <? elseif($field['Field']=='password'): ?>
                <div class="form-group">
                    <label for="input<?=$field['Field']?>"><?=t($field['Field'])?> <?=t('hash')?></label>
                    <input name="fields[<?=$field['Field']?>]" type="text" class="form-control" id="input<?=$field['Field']?>" value="<?=$element[$field['Field']]?>">
                    <label for="inputnew<?=$field['Field']?>"><?=t('New')?> <?=t($field['Field'])?></label>
                    <input name="fields[new_<?=$field['Field']?>]" type="text" class="form-control" id="inputnew<?=$field['Field']?>" value="">
                    <a href="#" onclick="return false;" class="help-block"><?=t('generate')?></a>
                </div>
            <? elseif($field['Type']=='text'): ?>
                <div class="form-group">
                    <label for="input<?=$field['Field']?>"><?=t($field['Field'])?></label>
                    <textarea name="fields[<?=$field['Field']?>]" class="form-control" rows="3"><?=$element[$field['Field']]?></textarea>
                </div>
            <? else:?>
                <div class="form-group">
                    <label for="input<?=$field['Field']?>"><?=t($field['Field'])?></label>
                    <input name="fields[<?=$field['Field']?>]" type="text" class="form-control" id="input<?=$field['Field']?>" value="<?=$element[$field['Field']]?>">
                </div>
            <? endif;?>
            <? endforeach;?>
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