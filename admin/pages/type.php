<?
//E::debug();
$type=E::getTypeById((int)$_GET['id']);

$type['fields']=array();
$type['fields']=E::getFullTypeFields($type);

$type['class']=E::getTypeClass($type['name']);
if(!class_exists($type['class']['name'])) require_once($type['class']['path']);
$params=array();
$params['type']=$type;
if(!empty($_GET['order'])) $params['order']=array($_GET['order'],$_GET['desc']);
$elements=$type['class']['name']::get($params);

?>
<p class="pull-left">
    <a href="element/act/add/type/<?=$type['id']?>" class="btn btn-success" data-target="#window" tabindex="1"><i class="icon-plus"></i> <?=t('Add')?></a>
    <a href="/admin/index.php?page=element&type=<?=$type['id']?>&act=edit" class="btn btn-primary disabled" data-target="#window" id="btn-edit" tabindex="2"><i class="icon-edit"></i> <?=t('Edit')?></a>
    <a href="/admin/index.php?page=element&type=<?=$type['id']?>&act=copy" class="btn btn-primary disabled" data-target="#window" id="btn-copy" tabindex="3"><i class="icon-copy"></i> <?=t('Copy')?></a>
    <a href="/admin/index.php?page=element&type=<?=$type['id']?>&act=delete" class="btn btn-danger disabled" data-target="#window" id="btn-delete" tabindex="4"><i class="icon-remove"></i> <?=t('Delete')?></a>
</p>
<p class="pull-right">
    <a href="/admin/index.php?page=type_form&act=edit&type=<?=$type['id']?>" class="btn btn-primary" data-target="#window"><i class="icon-cog"></i> <?=t('Settings')?></a>
    <a href="#/type_fields/id/<?=$type['id']?>" class="btn btn-primary"><i class="icon-cog"></i> <?=t('Fields')?></a>
    <a href="/admin/index.php?page=type_form" class="btn btn-primary" data-target="#window"><i class="icon-plus"></i> <?=t('Add subtype')?></a>
</p>

<table class="table table-hover table-condensed selectable">
    <thead>
    <tr>
        <th><a href="#/type/id/<?=$type['id']?>/order/<?=$field['name']?>">id</a></th>
        <? foreach($type['fields'] as $i=>$field):?>
            <?
            $field['class']='';
            if($field['name']===$_GET['order']) $field['class'].='order ';
            if(!empty($_GET['desc'])) $field['class'].='desc ';
            ?>
            <th><a href="#/type/id/<?=$type['id']?>/order/<?=$field['name']?><?=(empty($_GET['desc']) ? '/desc/1' :'')?>"<?=(!empty($field['class']) ? ' class="'.trim($field['class']).'"' :'')?>><?=t($field['name'])?></a></th>
        <? endforeach; ?>
    </tr>
    <tr class="filter">
        <th><a href="#/type/id/<?=$type['id']?>/order/<?=$field['name']?>">id</a></th>
        <? foreach($type['fields'] as $i=>$field):?>
            <?
            $field['class']='';
            if($field['name']===$_GET['order']) $field['class'].='order ';
            if(!empty($_GET['desc'])) $field['class'].='desc ';
            ?>
            <th><? Template::render('pages/field_types/field_filter.php',array('field'=>$field,'element'=>false, 'name'=>$field['name'])); ?></th>
        <? endforeach; ?>
    </tr>
    </thead>
    <tbody>
        <? foreach($elements as $element): ?>
            <tr>
                <td><input type="checkbox" name="elements[]" value="<?=$element['id']?>"> <?=$element['id']?></td>
            <? foreach($type['fields'] as $i=>$field):?>
                <td><?=(strlen($element[$field['name']])>100 ? trim(substr($element[$field['name']],0,100)).'...' : $element[$field['name']])?></td>
            <? endforeach; ?>
            </tr>
        <? endforeach; ?>
    </tbody>
    </tr>
</table>