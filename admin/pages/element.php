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
<? if(!empty($_FILES)):
    $uploaddir = $root_path.'/upload/files/';
    $uploadfile = $uploaddir . basename($_FILES['file']['name']);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) echo $_FILES['file']['name'];
    ?>
<? elseif(!empty($_POST['submit'])):
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
    <? if($act!='delete'):?>
    <? $connects=E::getConnectedTypes($type['id']); ?>
    <? if(!empty($connects)):?>
    <ul id="element-nav" class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab"><?=t('Properties')?></a></li>
        <? foreach($connects as $connect):?>
            <li class=""><a href="#<?=$connect['type']['name']?>" data-toggle="tab"><?=t($connect['type']['name'],true)?></a></li>
        <? endforeach;?>
    </ul>
    <? endif; ?>
    <? endif;?>

    <form method="POST" data-async data-target="#window .modal-body" action="/admin/index.php?page=element&type=<?=$type['id']?>&act=<?=$act?>" class="form-horizontal">
        <div class="tab-content">
            <div class="tab-pane fade active in" id="home">
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
                        <input name="fields[id]" type="hidden" value="<?=($act!='copy' ? $element['id']:'')?>">
                        <? foreach($type['fields'] as $i=>$field):?>
                        <? if($field['type']==='elements'): ?>
                            <?
                            //E::debug();
                            $fk_type=E::getType($field['elements_type']);
                            $fk_type['class']=E::getTypeClass($fk_type['name']);

                            if($fk_type['class']['name']::$foreign_select=='select'):
                                //E::debug();
                                $fk_elements=E::get(array('type'=>$fk_type['id'],'subtypes'=>true));
                                ?>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="input<?=$field['name']?>"><?=t($field['name'],true)?></label>
                                    <div class="col-lg-10">
                                        <select name="fields[<?=$field['name']?>]" id="input<?=$field['name']?>" class="form-control">
                                            <? if($field['Null']=='YES'):?><option value="NULL"><?=t('not set')?></option><? endif;?>
                                            <? foreach($fk_elements as $fk_element):?>
                                            <option value="<?=$fk_element['id']?>" <?=($fk_element['id']==$element[$field['name']] ? 'selected':'')?>><?=(empty($fk_element['name']) ? $fk_element['header'] : $fk_element['name'])?></option>
                                            <? endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            <? endif; ?>
                        <? elseif($field['type']==='enum'): ?>
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="input<?=$field['name']?>"><?=t($field['name'],true)?></label>
                                <div class="col-lg-10">
                                    <select name="fields[<?=$field['name']?>]" id="input<?=$field['name']?>" class="form-control">
                                        <? if($field['Null']=='YES'):?><option value="NULL"><?=t('not set')?></option><? endif;?>
                                        <? foreach($field['values'] as $val):?>
                                            <option value="<?=$val?>" <?=($val==$element[$field['name']] ? 'selected':'')?>><?=$val?></option>
                                        <? endforeach;?>
                                    </select>
                                </div>
                            </div>
                        <? elseif($field['type']==='image'|$field['type']=='file'): ?>
                             <? include("pages/field_types/file.php");?>
                        <? elseif($field['name']=='password'): ?>
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="input<?=$field['name']?>"><?=t($field['name'])?> <?=t('hash')?></label>
                                <div class="col-lg-10">
                                    <input name="fields[<?=$field['name']?>]" type="text" class="form-control" id="input<?=$field['name']?>" value="<?=$element[$field['name']]?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="inputnew<?=$field['name']?>"><?=t('New')?> <?=t($field['name'])?></label>
                                <div class="col-lg-10">
                                    <input name="fields[new_<?=$field['name']?>]" type="text" class="form-control" id="inputnew<?=$field['name']?>" value="">
                                    <a href="#" onclick="return false;" class="help-block"><?=t('generate')?></a>
                                </div>
                            </div>
                        <? elseif($field['type']=='text'): ?>
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="input<?=$field['name']?>"><?=t($field['name'],true)?></label>
                                <div class="col-lg-10">
                                    <textarea name="fields[<?=$field['name']?>]" id="input<?=$field['name']?>" class="form-control" rows="6" placeholder="Enter text ..." style="width:100%;"><?=$element[$field['name']]?></textarea>
                                </div>
                            </div>
                        <? elseif($field['type']=='html'): ?>
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="input<?=$field['name']?>"><?=t($field['name'],true)?></label>
                                <div class="col-lg-10">
                                <div id="toolbar<?=$field['name']?>" style="display: none;">
                                <? include($root_path."/admin/static/html/toolbar.tpl.html");?>
                                </div>

                                    <textarea name="fields[<?=$field['name']?>]" id="input<?=$field['name']?>" class="form-control" rows="6" placeholder="Enter text ..." style="width:100%;"><?=$element[$field['name']]?></textarea>
                                    <script>
                                        var editor = new wysihtml5.Editor("input<?=$field['name']?>", {
                                            toolbar:      "toolbar<?=$field['name']?>",
                                            //stylesheets:  "css/stylesheet.css",
                                            parserRules:  wysihtml5ParserRules
                                        });
                                    </script>
                                </div>
                            </div>
                        <? else:?>
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="input<?=$field['name']?>"><?=t($field['name'])?></label>
                                <div class="col-lg-10">
                                    <input name="fields[<?=$field['name']?>]" type="text" class="form-control" id="input<?=$field['name']?>" value="<?=$element[$field['name']]?>">
                                </div>
                            </div>
                        <? endif;?>
                        <? endforeach;?>
                        <div class="form-group">
                            <label class="col-lg-2 control-label"></label>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-success"><?=t($act)?></button>
                                <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
                            </div>
                        </div>
                        <input type="hidden" name="fields[type]" value="<?=$type['id']?>">
                        <input type="hidden" name="submit" value="submit">
                    </fieldset>
                <?endif;?>
            </div>
            <? if($act!='delete'):?>
            <? foreach($connects as $connect):?>
                <div class="tab-pane fade" id="<?=$connect['type']['name']?>">
                    <?
                    //E::debug();
                    $cElements=E::get(array('filter'=>"`".$connect['field']."`='".$element['element_id']."'",'type'=>$connect['type']));
                    print_r($cElements);
                    ?>
                </div>
            <? endforeach;?>
            <? endif; ?>

        </div>
    </form>
    <div class="clearfix"></div>
<? endif;?>