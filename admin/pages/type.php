<?
//E::debug();
$type=E::getTypeById((int)$_GET['id']);

$type['fields']=array();
$type['fields']=E::getFullTypeFields($type);

$type['class']=E::getTypeClass($type['name']);
if(!class_exists($type['class']['name'])) require_once($type['class']['path']);
$params=array('type'=>$type);
$elements=$type['class']['name']::get($params);

?>
<p class="pull-left">
    <a href="element/act/add/type/<?=$type['id']?>" class="btn btn-success" data-target="#window" tabindex="1"><i class="icon-plus"></i> <?=t('Add')?></a>
    <a href="/admin/index.php?page=element&type=<?=$type['id']?>&act=edit" class="btn btn-primary disabled" data-target="#window" id="btn-edit" tabindex="2"><i class="icon-edit"></i> <?=t('Edit')?></a>
    <a href="element/act/copy/type/<?=$type['id']?>" class="btn btn-primary disabled" data-target="#window" id="btn-copy" tabindex="3"><i class="icon-copy"></i> <?=t('Copy')?></a>
    <a href="/admin/index.php?page=element&type=<?=$type['id']?>&act=delete" class="btn btn-danger disabled" data-target="#window" id="btn-delete" tabindex="4"><i class="icon-remove"></i> <?=t('Delete')?></a>
</p>
<p class="pull-right">
    <a href="/admin/index.php?page=type_form&act=edit&type=<?=$type['id']?>" class="btn btn-primary" data-target="#window"><i class="icon-cog"></i> <?=t('Settings')?></a>
    <a href="#/type_fields/id/<?=$type['id']?>" class="btn btn-primary"><i class="icon-cog"></i> <?=t('Fields')?></a>
    <a href="/admin/index.php?page=type_form" class="btn btn-primary" data-target="#window"><i class="icon-plus"></i> <?=t('Add subtype')?></a>
</p>

<table id="elements" class="table table-hover table-condensed">
    <tr>
        <th><a href="#/type/id/id/<?=$type['id']?>/sort/<?=$field['name']?>">id</a></th>
        <? foreach($type['fields'] as $i=>$field):?>
           <th><a href="#/type/id/id/<?=$type['id']?>/sort/<?=$field['name']?>"><?=t($field['name'])?></a></th>
        <? endforeach; ?>
        <? foreach($elements as $element): ?>
            <tr>
                <td><input type="checkbox" name="elements[]" value="<?=$element['id']?>"> <?=$element['id']?></td>
            <? foreach($type['fields'] as $i=>$field):?>
                <td><?=$element[$field['name']]?></td>
            <? endforeach; ?>
            </tr>
        <? endforeach; ?>
    </tr>
</table>