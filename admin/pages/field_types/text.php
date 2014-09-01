<?
$field=$data['field'];
$element=$data['element'];
?>
<textarea name="<?=$data['name']?>" id="<?=(empty($data['id']) ? 'input_'.$field['name'] : $data['id'])?>" class="form-control" rows="6" <?=(!empty($data['field']['placeholder']) ? 'placeholder="'.$data['field']['placeholder'].'"' : '')?> style="width:100%;" data-field="<?=$field['name']?>"><?=$data['value']?></textarea>
<? if(!empty($data['field']['help'])):?>
    <span class="help-block"><?=$data['field']['help']?></span>
<? endif;?>