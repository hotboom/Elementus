<? $id=(empty($data['id']) ? 'input_'.$data['field']['name'] : $data['id']); ?>
<input name="<?=$data['name']?>" type="text" class="form-control" id="<?=$id?>" value="<?=$data['value']?>" data-field="<?=$data['field']['name']?>" data-date-format="yyyy-mm-dd">
<script>
    $(function(){
        $('#<?=$id?>').datepicker();
    });
</script>