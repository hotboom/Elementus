<?
//E::debug();
$type_id=(int)$_GET['type'];
$type=E::getType($type_id);
$types=E::getFullType($type_id);
$type['fields']=E::getFullTypeFields($type);

$type['class']=E::getTypeClass($type);
if(!class_exists($type['class']['name'])) require_once($root_path."/core/types/".$type['name'].".php");

$act=htmlspecialchars($_GET['act']);
$elements=array('id'=>false);
$connects=E::getConnectedTypes($type['id']);

if(empty($_GET['hide'])) $_GET['hide']=array();
if(empty($_GET['fields'])) $_GET['fields']=array();
if(empty($_GET['target'])) $target='.modal-body';
else $target=htmlspecialchars($_GET['target']);

if(($act=='edit'|$act=='copy')&&empty($_POST['submit'])) {
    $elements=$_GET['elements'];
    foreach($elements as $i=>$element) $elements[$i]=E::getById((int)$element);
    if(count($elements)==1) $element=$elements[0];
    else $element=array();
}
?>
<? if(!empty($_FILES)):
    $_FILES['file']['name']=substr(md5(time().rand(0,99)),0,20).'.'.substr($_FILES['file']['name'], strrpos($_FILES['file']['name'], '.') + 1);
    $uploaddir = $root_path.'/upload/files/';
    $uploadfile = $uploaddir . basename($_FILES['file']['name']);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) echo $_FILES['file']['name'];
?>
<? elseif(!empty($_POST['submit'])):
    //E::debug();
    if($act=='delete') $result=$type['class']['name']::delete($_POST['elements']);
    else {
        $fields=$_POST['fields'];
        $result=$type['class']['name']::set($fields);

        if(!empty($_POST['connected'])) {
            foreach($_POST['connected'] as $connected) {
                if(!empty($connected['delete'])&&!empty($connected['id'])) $type['class']['name']::delete($connected['id']);
                $type['class']['name']::set($connected);
            }
        }
    }
    ?>
    <? if($result):?>
        <div class="alert alert-success"><?=t($type['name'].' succesfuly '.$act)?></div>
    <? else:?>
        <? foreach(E::$errors as $error):?>
        <div class="alert alert-warning"><?=t('Error occurred:'.$error['desc'])?></div>
        <? endforeach;?>
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
    <? if($act!='delete'&&$_GET['tabs']!='no'):?>
        <? if(!empty($connects)):?>
        <ul id="element-nav" class="nav nav-tabs">
            <li class="active"><a href="#home" data-toggle="tab"><?=t('Properties')?></a></li>
            <? foreach($connects as $connect):?>
                <li class=""><a href="#<?=$connect['type']['name']?>" data-toggle="tab"><?=t($connect['type']['name'],true)?></a></li>
            <? endforeach;?>
        </ul>
        <? endif; ?>
    <? endif;?>
    <?if(empty($_GET['into'])):?>
    <form method="POST" data-async data-target="<?=$target?>" action="/admin/index.php?page=element&type=<?=$type['id']?>&act=<?=$act?>" class="form-horizontal">
    <?endif;?>
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
                        <?
                        $prefix='fields';
                        if(!empty($_GET['into'])){
                            $prefix=htmlspecialchars($_GET['into']);
                        }
                        ?>
                        <? foreach($elements as $e): ?>
                        <input name="<?=$prefix?>[id][]" type="hidden" value="<?=($act!='copy' ? $e['id']:'')?>">
                        <? endforeach;?>
                        <? if(count($elements)>1):?>
                            <div class="alert alert-info"><strong><?=t('Batch')?></strong>: <?=t('changes will be applied to '.count($elements).'  elements, blank fields has no effect')?></div>
                        <? endif;?>
                        <? foreach($type['fields'] as $i=>$field):
                            //if(in_array($field['name'],$_GET['hide'])) continue;
                            foreach($_GET['fields'] as $i=>$v) $element[$i]=$v;
                            $name='fields['.$field['name'].']';
                            if($field['multiple']) $tmp='pages/field_types/field_group_hor_multy.php';
                            else                   $tmp='pages/field_types/field_group_hor.php';

                            Template::render($tmp,array(
                                'field'=>$field,
                                'value'=>$element[$field['name']],
                                'name'=>$prefix.'['.$field['name'].']',
                                'id'=>'input_'.$field['name']
                            ));

                        endforeach;?>
                        <? if($_GET['buttons']!=='no'):?>
                        <div class="form-group">
                            <label class="col-lg-2 control-label"></label>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-success"><?=t($act)?><? if(count($elements)>1) echo ' '.t('all ('.count($elements).')'); ?></button>
                                <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
                            </div>
                        </div>
                        <? endif;?>
                        <input type="hidden" name="fields[type]" value="<?=$type['id']?>">
                        <input type="hidden" name="submit" value="submit">
                    </fieldset>
                <?endif;?>
            </div>
            <? if($act!='delete'&&$_GET['tabs']!=='no'):?>
                <? foreach($connects as $connect):?>
                    <div class="tab-pane fade" id="<?=$connect['type']['name']?>" data-ajax="/admin/index.php?page=type&filters=no&advanced=no&id=<?=$connect['type']['id']?>&filter[<?=$connect['field']?>]=<?=$element['id']?>&hide[]=<?=$connect['field']?>&target=%23<?=$connect['type']['name']?>&fields[<?=$connect['field']?>]=<?=$element['id']?>">
                        <?=t('Loading').'...'?>
                    </div>
                <? endforeach;?>
            <? endif; ?>
        </div>
    <?if(empty($_GET['into'])):?>
    </form>
    <?endif?>
    <div class="clearfix"></div>
    <script>
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var url=$('.tab-content .tab-pane.active').attr('data-ajax');
            if(url){
                $.ajax({
                    url: url,
                    cache:false,
                    success: function(data){
                        $('.tab-content .tab-pane.active').html(data);
                    }
                });
            }

        });
    </script>
<? endif;?>