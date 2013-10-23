<?
$field=$data['field'];
$element=$data['element'];
$fk_type=E::getType($field['elements_type']);
$fk_type['class']=E::getTypeClass($fk_type['name']);

$fk_elements=E::get(array('type'=>$fk_type['id'],'subtypes'=>true));

?>
<select name="<?=$data['name']?>" id="<?=(empty($data['id']) ? 'input_'.$field['name'] : $data['id'])?>" class="form-control" data-field="<?=$field['name']?>">
    <? if($field['nullable']):?><option value=""><?=t('not set')?></option><? endif;?>
    <? foreach($fk_elements as $fk_element):?>
        <option value="<?=$fk_element['id']?>" <?=($fk_element['id']==$data['value'] ? 'selected':'')?>><?=(empty($fk_element['name']) ? $fk_element['header'] : $fk_element['name'])?></option>
    <? endforeach;?>
</select>