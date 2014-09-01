<?
$field=$data['field'];
$element=$data['element'];
?>
<select name="<?=$data['name']?>" id="<?=(empty($data['id']) ? 'input_'.$field['name'] : $data['id'])?>" class="form-control selectpicker" data-field="<?=$field['name']?>">
    <option value="" <?=(empty($data['value']) ? 'selected="selected"':'')?>><?=t('not set')?></option>
    <? foreach($field['values'] as $val):?>
        <option value="<?=$val?>" <?=($val==$data['value']|$val==$field['default'] ? 'selected':'')?>><?=$val?></option>
    <? endforeach;?>
</select>
<? if(!empty($data['field']['help'])):?>
    <span class="help-block"><?=$data['field']['help']?></span>
<? endif;?>