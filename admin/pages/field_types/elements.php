<?
$field=$data['field'];
$element=$data['element'];
$fk_type=E::getType($field['elements_type']);
$fk_type['class']=E::getTypeClass($fk_type['name']);

$fk_elements=E::get(array('type'=>$fk_type['id'],'subtypes'=>true));

?>
<select name="<?=$field['name']?>" id="input<?=$field['name']?>" class="form-control" data-field="<?=$field['name']?>">
    <? if($field['nullable']):?><option value="NULL"><?=t('not set')?></option><? endif;?>
    <? foreach($fk_elements as $fk_element):?>
        <option value="<?=$fk_element['id']?>" <?=($fk_element['id']==$element[$field['name']] ? 'selected':'')?>><?=(empty($fk_element['name']) ? $fk_element['header'] : $fk_element['name'])?></option>
    <? endforeach;?>
</select>