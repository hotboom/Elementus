<?
$field=$data['field'];
$element=$data['element'];
?>
<textarea name="<?=$data['name']?>" id="input<?=$field['name']?>" class="form-control" rows="6" placeholder="Enter text ..." style="width:100%;"><?=$element[$field['name']]?></textarea>