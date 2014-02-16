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
        <?elseif($field['type']==='datetime'): ?>
            <? Template::render('pages/field_types/datetime.php',$data); ?>
        <?else: ?>
            <? Template::render('pages/field_types/default.php',$data); ?>
        <?endif; ?>
    </div>
</div>
<? else: ?>
    <? Template::render('pages/field_types/password.php',$data); ?>
<? endif;?>