<input name="<?=$data['name']?>" type="text" class="form-control" id="<?=(empty($data['id']) ? 'input_'.$data['field']['name'] : $data['id'])?>" value="<?=$data['value']?>" data-field="<?=$data['field']['name']?>" <?=(!empty($data['field']['placeholder']) ? 'placeholder="'.t($data['field']['placeholder']).'"' : '')?>>