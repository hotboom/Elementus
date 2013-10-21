<?
$field=$data['field'];
$element=$data['element'];
?>
<? if($field['name']!='password'): ?>
<div class="form-group">
    <label class="col-lg-2 control-label" for="input<?=$field['name']?>"><?=t($field['name'],true)?></label>
    <div class="col-lg-10">
        <?if($field['type']==='elements'):?>
            <? Template::render('pages/field_types/elements.php',$data); ?>
        <?elseif($field['type']==='enum'): ?>
            <? Template::render('pages/field_types/enum.php',$data); ?>
        <?elseif($field['type']==='text'): ?>
            <? Template::render('pages/field_types/text.php',$data); ?>
        <?elseif($field['type']==='html'): ?>
            <? Template::render('pages/field_types/html.php',$data); ?>
        <?elseif($field['type']==='file'|$field['type']==='image'): ?>
            <? Template::render('pages/field_types/file.php',$data); ?>
        <?else: ?>
            <? Template::render('pages/field_types/default.php',$data); ?>
        <?endif; ?>
    </div>
</div>
<? else: ?>
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
<? endif;?>