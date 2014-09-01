<?
$field=$data['field'];
$element=$data['element'];
$field['id']=(empty($data['id']) ? 'input_'.$field['name'] : $data['id'])
?>
<textarea name="<?=$data['name']?>" id="<?=$field['id']?>" name="<?=$field['id']?>" class="form-control" rows="24" placeholder="Enter text ..." style="width:100%;" data-field="<?=$field['name']?>"><?=$data['value']?></textarea>
<script>
    $( document ).ready( function() {
        $( '#<?=$field['id']?>' ).ckeditor({
            customConfig: '/admin/static/js/ckeditor_config.js'
        });
    });
</script>
<? if(!empty($data['field']['help'])):?>
    <span class="help-block"><?=$data['field']['help']?></span>
<? endif;?>