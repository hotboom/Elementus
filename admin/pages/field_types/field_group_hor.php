<?
$field=$data['field'];
$element=$data['element'];
$path='pages/field_types/';
?>
<? if($field['name']!='password'): ?>
<div class="form-group">
    <label class="col-lg-2 control-label" for="input<?=$field['name']?>"><?=t($field['name'],true)?></label>
    <div class="col-lg-10">
        <?if($field['type']==='elements'):?>
            <? Template::render($path.'elements.php',$data); ?>
        <?elseif($field['type']==='enum'): ?>
            <? Template::render($path.'enum.php',$data); ?>
        <?elseif($field['type']==='text'): ?>
            <? Template::render($path.'text.php',$data); ?>
        <?elseif($field['type']==='html'): ?>
            <? Template::render($path.'html.php',$data); ?>
        <?elseif($field['type']==='file'|$field['type']==='image'): ?>
            <? Template::render($path.'file.php',$data); ?>
        <?elseif($field['type']==='datetime'|$field['type']==='date'): ?>
            <? Template::render($path.'datetime.php',$data); ?>
        <?else: ?>
            <? Template::render($path.'default.php',$data); ?>
        <?endif; ?>
    </div>
</div>
<? else: ?>
    <? Template::render($path.'password.php',$data); ?>
<? endif;?>