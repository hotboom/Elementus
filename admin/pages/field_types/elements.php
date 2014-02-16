<?
$field=$data['field'];
$element=$data['element'];
$type=E::getType($field['elements_type']);
$type['class']=E::getTypeClass($type['name']);
$fields=E::getTypeFields($type['id']);
$type['tree']=false;
foreach($fields as $field){
    if($field['elements_type']==$type['name']) {
        $type['tree']=$field;
    }
}
//print_r($fields);
//print_r($type);
$elements=E::get(array('type'=>$type['id'],'subtypes'=>true,'limit'=>999));

?>
<? if(!$type['tree']):?>
<select name="<?=$data['name']?>" id="<?=(empty($data['id']) ? 'input_'.$data['field']['name'] : $data['id'])?>" class="form-control selectpicker" data-field="<?=$data['field']['name']?>" data-live-search="true">
    <? if($field['nullable']):?><option value=""><?=t('not set')?></option><? endif;?>
    <? foreach($elements as $element):?>
        <option value="<?=$element['id']?>" <?=($element['id']==$data['value'] ? 'selected':'')?>><?=(empty($element['name']) ? $element['header'] : $element['name'])?></option>
    <? endforeach;?>
</select>
<? else:
    if(!function_exists('tree')){
        function tree($type, $data, $parent_id=false, $class=false){
            $elements=E::get(array(
                'type'=>$type,
                'filter'=>array($type['tree']['name']=>$parent_id)
            ));
            if(empty($elements)) return false;

            echo '<ul'.(!empty($class) ? ' class="'.$class.'"':'').'>';
            foreach($elements as $element){
                echo '<li><input type="radio" name="'.$data['name'].'" value="'.$element['id'].'" id="input_'.$data['field']['name'].'_'.$element['id'].'">';
                echo '<label for="input_'.$data['field']['name'].'_'.$element['id'].'">'.(empty($element['name']) ? $element['header'] : $element['name']).'</label>';
                tree($type, $data, $element['id']);
                echo '</li>';
            }
            echo '</ul>';
            return true;
        }
        ?>
        <div class="btn-group bootstrap-select form-control">
            <button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" data-id="input_show">
                <div class="filter-option pull-left">Раздел</div>
                &nbsp;
                <div class="caret"></div>
            </button>
            <div class="dropdown-menu open">
                <? tree($type, $data); ?>
                <ul class="dropdown-menu inner" role="menu">
                    <li rel="0"><a tabindex="0" class="" style=""><span class="text">не задано</span><i class="glyphicon glyphicon-ok icon-ok check-mark"></i></a></li>
                    <li rel="1" class="selected"><a tabindex="0" class="" style=""><span class="text">1</span><i class="glyphicon glyphicon-ok icon-ok check-mark"></i></a></li>
                    <li rel="2"><a tabindex="0" class="" style=""><span class="text">0</span><i class="glyphicon glyphicon-ok icon-ok check-mark"></i></a></li>
                </ul>
            </div>
        </div>
        <?
    }
endif;?>