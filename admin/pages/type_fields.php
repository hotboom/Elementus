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
    <a href="field_form/act/add/type/<?=$type['id']?>" class="btn btn-success" data-target="#window"><i class="icon-plus"></i> <?=t('Add')?></a>
    <a href="/admin/index.php?page=field_form&type=<?=$type['id']?>&act=edit" class="btn btn-primary disabled" data-target="#window" id="btn-edit"><i class="icon-edit"></i> <?=t('Edit')?></a>
    <a href="field_form/act/copy/type/<?=$type['id']?>" class="btn btn-primary disabled" data-target="#window" id="btn-copy"><i class="icon-copy"></i> <?=t('Copy')?></a>
    <a href="/admin/index.php?page=field_form&type=<?=$type['id']?>&act=delete" class="btn btn-danger disabled" data-target="#window" id="btn-delete"><i class="icon-remove"></i> <?=t('Delete')?></a>
</p>

<p class="pull-right">
    <a href="#/type/id/<?=$type['id']?>" class="btn btn-primary"><i class="icon-arrow-left"></i> <?=t('Back to elements')?></a>
</p>

<table id="elements" class="table table-hover table-condensed">
    <tr>
        <th>#</th>
        <th><?=t('Name')?></th>
        <th><?=t('Type')?></th>
        <th><?=t('Default')?></th>
    </tr>
    <? foreach($type['fields'] as $i=>$field):?>
    <tr>
        <td><input type="checkbox" name="fields[]" value="<?=$field['Field']?>"> <?=$i?></td>
        <td><?=$field['Field']?></td>
        <td><?=$field['Type']?></td>
        <td><?=$field['Default']?></td>
    </tr>
    <? endforeach; ?>
</table>