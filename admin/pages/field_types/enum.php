<?
$field=$data['field'];
$element=$data['element'];
?>
<select name="<?=$data['name']?>" id="input<?=$field['name']?>" class="form-control">
    <? if($field['Null']=='YES'):?><option value="NULL"><?=t('not set')?></option><? endif;?>
    <? foreach($field['values'] as $val):?>
        <option value="<?=$val?>" <?=($val==$element[$field['name']] ? 'selected':'')?>><?=$val?></option>
    <? endforeach;?>
</select>