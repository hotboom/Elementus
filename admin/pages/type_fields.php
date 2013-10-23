<?
//E::debug();
$type_id=(int)$_GET['id'];
$type=E::getType($type_id);

$types=E::getFullType($type_id);
$type['fields']=array();
$type['fields']=E::getTypeFields($type);

$type['class']=E::getTypeClass($type['name']);
if(!class_exists($type['class']['name'])) require_once($type['class']['path']);
$params=array('type'=>$type['name']);
//print_r($type['fields']);
?>
<p class="pull-left">
    <a href="field_form/act/add/type/<?=$type['id']?>" class="btn btn-success" data-target="#window"><i class="fa fa-plus"></i> <?=t('Add')?></a>
    <a href="/admin/index.php?page=field_form&type=<?=$type['id']?>&act=edit" class="btn btn-primary disabled" data-target="#window" id="btn-edit"><i class="fa fa-edit"></i> <?=t('Edit')?></a>
    <a href="field_form/act/copy/type/<?=$type['id']?>" class="btn btn-primary disabled" data-target="#window" id="btn-copy"><i class="fa fa-copy"></i> <?=t('Copy')?></a>
    <a href="/admin/index.php?page=field_form&type=<?=$type['id']?>&act=delete" class="btn btn-danger disabled" data-target="#window" id="btn-delete"><i class="fa fa-times"></i> <?=t('Delete')?></a>
</p>

<p class="pull-right">
    <a href="#/type/id/<?=$type['id']?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> <?=t('Back to elements')?></a>
</p>

<table class="table table-hover table-condensed selectable">
    <tr>
        <th>#</th>
        <th><?=t('Name')?></th>
        <th><?=t('Type')?></th>
        <th><?=t('Default')?></th>
    </tr>
    <? foreach($type['fields'] as $i=>$field):?>
    <tr>
        <td><input type="checkbox" name="fields[]" value="<?=$field['name']?>"> <?=$i?></td>
        <td><?=$field['name']?></td>
        <td><?=$field['type']?></td>
        <td><?=$field['default']?></td>
    </tr>
    <? endforeach; ?>
</table>