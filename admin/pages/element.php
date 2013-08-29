<?
$type_id=(int)$_GET['type'];
$type=E::getType($type_id);
$types=E::getFullType($type_id);
$type['fields']=E::getFullTypeFields($type);

$type['class']=E::getTypeClass($type['name']);
if(!class_exists($type['class']['name'])) require_once($root_path."/core/classes/".$type['name'].".php");

$act=htmlspecialchars($_GET['act']);
$element=array();
if($act=='edit'|$act=='copy') {
    $element_id=(int)$_GET['elements'][0];
    $element=E::getById($element_id);
}
?>

<? if(!empty($_POST['submit'])):
    //echo '<pre>'.print_r($_POST['fields']).'</pre>';
    //E::debug();

    if($act=='delete') $result=$type['class']['name']::delete($_POST['elements']);
    else $result=$type['class']['name']::set($_POST['fields']);
    ?>
    <? if($result):?>
        <div class="alert alert-success"><?=t($type['name'].' succesfuly '.$act)?></div>
    <? else:?>
        <div class="alert alert-warning"><?=t('Error occurred:'.E::$error['desc'])?></div>
    <? endif;?>
    <script>
        $(window).hashchange();
        $(function() {
            $('.modal-title').html('<?=t($type['name'])?> <?=t('added')?> ');
            $('.modal-footer').show();
        });
    </script>
<? else:?>
    <script>
        $(function() {
            $('.modal-title').html('<?=t($act.' '.$type['name'],true)?>');
            $('.modal-footer').hide();
        });
    </script>
    <form method="POST" data-async data-target="#window .modal-body" action="/admin/index.php?page=element&type=<?=$type['id']?>&act=<?=$act?>">
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
            <input name="fields[id]" type="hidden" value="<?=($act!='copy' ? $element['element_id']:'')?>">
            <? foreach($type['fields'] as $i=>$field):?>
            <? if(!empty($field['FK'])): ?>
                <?
                $fk_type=E::getTypeByName($field['FK']);
                $fk_type['class']=E::getTypeClass($fk_type['name']);

                if($fk_type['class']['name']::$foreign_select=='select'):
                    $fk_elements=E::get(array('type'=>$fk_type['id']));
                    ?>
                    <div class="form-group">
                        <label for="input<?=$field['Field']?>"><?=t($field['Field'],true)?></label>
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
                    <label for="input<?=$field['Field']?>"><?=t($field['Field'],true)?></label>
                    <div id="toolbar<?=$field['Field']?>" style="display: none;">
                    <? include($root_path."/admin/static/html/toolbar.tpl.html");?>
                    </div>
                    <textarea name="fields[<?=$field['Field']?>]" id="input<?=$field['Field']?>" class="form-control" rows="6" placeholder="Enter text ..." style="width:100%;"><?=$element[$field['Field']]?></textarea>
                    <script>
                        var editor = new wysihtml5.Editor("input<?=$field['Field']?>", {
                            toolbar:      "toolbar<?=$field['Field']?>",
                            //stylesheets:  "css/stylesheet.css",
                            parserRules:  wysihtml5ParserRules
                        });
                    </script>
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
    <div class="clearfix"></div>
<? endif;?>