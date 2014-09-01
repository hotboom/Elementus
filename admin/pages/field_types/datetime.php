<? $id=(empty($data['id']) ? 'input_'.$data['field']['name'] : $data['id']); ?>
<div class="input-group date <?=($data['field']['type']=='datetime' ? 'datetimepicker' : 'datepicker')?>">
    <input name="<?=$data['name']?>" type='text' class="form-control" id="<?=$id?>" value="<?=$data['value']?>" data-field="<?=$data['field']['name']?>" data-format="YYYY-MM-DD hh:mm" />
    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
</div>
<? if(!empty($data['field']['help'])):?>
    <span class="help-block"><?=$data['field']['help']?></span>
<? endif;?>