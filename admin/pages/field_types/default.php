<input name="<?=$data['name']?>" type="text" class="form-control" id="<?=(empty($data['id']) ? 'input_'.$data['field']['name'] : $data['id'])?>" value="<?=htmlspecialchars($data['value'])?>" data-field="<?=$data['field']['name']?>" <?=(!empty($data['field']['placeholder']) ? 'placeholder="'.$data['field']['placeholder'].'"' : '')?>>
<? if(!empty($data['field']['help'])):?>
    <span class="help-block"><?=$data['field']['help']?></span>
<? endif;?>