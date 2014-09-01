<?
$field=$data['field'];
$element=$data['element'];
$id=(empty($data['id']) ? 'input_'.$field['name'] : $data['id']);
?>
<?if(!$field['multyple']):?><div class="input-group"><?endif?>
<? if(!empty($data['value'])):?>
<span class="input-group-btn">
    <a href="/upload/files/<?=$data['value']?>" class="btn btn-default" data-hover="popover" data-placement="top"><i class="fa fa-download"></i></a>
</span>
<? endif;?>
<input name="<?=$data['name']?>" type="text" class="form-control" id="<?=$id?>" value="<?=$data['value']?>" data-field="<?=$field['name']?>">
<span class="input-group-btn">
    <a class="btn btn-success fileupload-button" data-fileupload-action="/admin/index.php?page=element&type=<?=$type['id']?>&act=<?=$act?>">
        <i class="fa fa-plus"></i>
        <span><?=t('Upload...')?></span>
    </a>
    <a class="btn btn-default" onclick="openKCFinder('#<?=$id?>');">
        <i class="fa fa-plus"></i>
        <span><?=t('Select from server...')?></span>
    </a>
</span>
<?if(!$field['multyple']):?></div><?endif?>
<? if(!empty($data['field']['help'])):?>
    <span class="help-block"><?=$data['field']['help']?></span>
<? endif;?>