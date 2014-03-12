<? $id=(empty($data['id']) ? 'input_'.$data['field']['name'] : $data['id']); ?>
<div class='input-group date' id='datetimepicker1'>
    <input name="<?=$data['name']?>" type='text' class="form-control" id="<?=$id?>" value="<?=$data['value']?>" data-field="<?=$data['field']['name']?>" data-format="YYYY-MM-DD hh:mm" />
    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
</div>
<script>
    $(function(){
        $('#datetimepicker1').datetimepicker({
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            },
            language: 'ru'
        });
    });
</script>