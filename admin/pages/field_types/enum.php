<?
$field=$data['field'];
$element=$data['element'];
?>
<select name="<?=$data['name']?>" id="<?=(empty($data['id']) ? 'input_'.$field['name'] : $data['id'])?>" class="form-control selectpicker" data-field="<?=$field['name']?>">
    <option value=""><?=t('not set')?></option>
    <? foreach($field['values'] as $val):?>
        <option value="<?=$val?>" <?=($val==$data['value']|$val==$field['default'] ? 'selected':'')?>><?=$val?></option>
    <? endforeach;?>
</select>