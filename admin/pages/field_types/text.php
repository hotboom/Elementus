<?
$field=$data['field'];
$element=$data['element'];
?>
<textarea name="<?=$data['name']?>" id="<?=(empty($data['id']) ? 'input_'.$field['name'] : $data['id'])?>" class="form-control" rows="6" placeholder="Enter text ..." style="width:100%;" data-field="<?=$field['name']?>"><?=$data['value']?></textarea>