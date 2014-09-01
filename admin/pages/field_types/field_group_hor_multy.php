<?
$field=$data['field'];
$element=$data['element'];
$value=$data['value'];
$path='pages/field_types/';
if(empty($value)) $value=array('');
foreach($value as $i=>$val):
    $data_curr=$data;
    $data_curr['id'].='_'.$i;
    $data_curr['value']=$val;
    $data_curr['name'].='['.$i.']'; ?>
    <? if($field['name']!='password'): ?>
    <div class="form-group group_<?=$field['name']?>">
        <label class="col-lg-2 control-label" for="input_<?=$field['name']?>"><?=t($field['name'],true)?></label>
        <div class="col-lg-10">
            <div class="input-group">
                <?if($field['type']==='elements'):?>
                    <? Template::render($path.'elements.php',$data_curr); ?>
                <?elseif($field['type']==='enum'): ?>
                    <? Template::render($path.'enum.php',$data_curr); ?>
                <?elseif($field['type']==='text'): ?>
                    <? Template::render($path.'text.php',$data_curr); ?>
                <?elseif($field['type']==='html'): ?>
                    <? Template::render($path.'html.php',$data_curr); ?>
                <?elseif($field['type']==='file'|$field['type']==='image'): ?>
                    <? Template::render($path.'file.php',$data_curr); ?>
                <?elseif($field['type']==='datetime'|$field['type']==='date'): ?>
                    <? Template::render($path.'datetime.php',$data_curr); ?>
                <?else: ?>
                    <? Template::render($path.'default.php',$data_curr); ?>
                <?endif; ?>
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" onclick="$(this).parents('.group_<?=$field['name']?>').remove()"><span class="fa fa-times"></span></button>
                </span>
            </div>
        </div>
    </div>
    <? else: ?>
        <? Template::render($path.'password.php',$data); ?>
    <? endif;?>
<? endforeach;?>
<div class="form-group">
    <div class="col-lg-10 col-lg-offset-2">
        <a href="#" class="btn btn-info btn-sm addField" data-clone="group_<?=$field['name']?>"><i class="fa fa-plus-circle"></i> <?=t('Add more')?></a>
    </div>
</div>
