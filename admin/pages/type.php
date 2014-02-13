<?
//E::debug();
$type=E::getTypeById((int)$_GET['id']);
if(empty($_GET['mode'])) $_GET['mode']='normal';

$type['fields']=array();
$type['fields']=E::getFullTypeFields($type);

foreach($type['fields'] as $i=>$field){
    if($type['fields'][$i]['hide']) unset($type['fields'][$i]);
}

$type['class']=E::getTypeClass($type['name']);
if(!class_exists($type['class']['name'])) require_once($type['class']['path']);
$params=array();
if(!empty($_GET['p'])) $params['page']=(int)$_GET['p'];
$params['type']=$type;
if(!empty($_GET['order'])) $params['order']=array($_GET['order'],$_GET['desc']);
if(!empty($_GET['filter'])) {
    $params['filter']='';
    if(is_array($_GET['filter'])) {
        $i=0;
        foreach($_GET['filter'] as $name=>$val) {
            $params['filter'].=($i!=0 ? 'AND ': '')."CONCAT(' ',`".$name."`) LIKE '%".$val."%' ";
            $i++;
        }

    }
}
//E::debug();
$elements=$type['class']['name']::get($params);

foreach($elements as $i=>$element){
    foreach($type['fields'] as $field){
        if($field['type']=='elements') {
            $el=E::getById($elements[$i][$field['name']]);
            if(!empty($el['name'])) $elements[$i][$field['name']]=$el['name'];
            if(!empty($el['header'])) $elements[$i][$field['name']]=$el['header'];
        }
    }
}

?>
<div class="row">
<? if($_GET['mode']!='compact'): ?>
    <p class="pull-left">
        <a href="element/act/add/type/<?=$type['id']?>" class="btn btn-success" data-target="#window" tabindex="1"><i class="fa fa-plus"></i> <?=t('Add')?></a>
        <a href="/admin/index.php?page=element&type=<?=$type['id']?>&act=edit" class="btn btn-primary disabled" data-target="#window" id="btn-edit" tabindex="2"><i class="fa fa-edit"></i> <?=t('Edit')?></a>
        <a href="/admin/index.php?page=element&type=<?=$type['id']?>&act=copy" class="btn btn-primary disabled" data-target="#window" id="btn-copy" tabindex="3"><i class="fa fa-copy"></i> <?=t('Copy')?></a>
        <a href="/admin/index.php?page=element&type=<?=$type['id']?>&act=delete" class="btn btn-danger disabled" data-target="#window" id="btn-delete" tabindex="4"><i class="fa fa-times"></i> <?=t('Delete')?></a>
    </p>
<? endif;?>
<? if($_GET['mode']=='normal'): ?>
<p class="pull-right">
    <a href="/admin/index.php?page=type_form&act=edit&type=<?=$type['id']?>" class="btn btn-primary" data-target="#window"><i class="fa fa-cog"></i> <?=t('Settings')?></a>
    <a href="#/type_fields/id/<?=$type['id']?>" class="btn btn-primary"><i class="fa fa-cog"></i> <?=t('Fields')?></a>
    <a href="import/type/<?=$type['id']?>" data-target="#window" class="btn btn-primary"><i class="fa fa-reply-all"></i> <?=t('Import')?></a>
    <a href="/admin/index.php?page=type_form&parent=<?=$type['id']?>" class="btn btn-primary" data-target="#window"><i class="fa fa-plus"></i> <?=t('Add subtype')?></a>
</p>
<? endif; ?>
</div>

<table class="table table-hover table-condensed selectable">
    <thead>
    <tr>
        <th><a href="#/type/id/<?=$type['id']?>/order/<?=$field['name']?>">id</a></th>
        <? foreach($type['fields'] as $i=>$field):?>
            <?
            $field['class']='';
            if($field['name']===$_GET['order']) $field['class'].='order ';
            if(!empty($_GET['desc'])) $field['class'].='desc ';
            ?>
            <th><a href="#/type/id/<?=$type['id']?>/order/<?=$field['name']?><?=(empty($_GET['desc']) ? '/desc/1' :'')?>"<?=(!empty($field['class']) ? ' class="'.trim($field['class']).'"' :'')?>><?=t($field['name'])?></a></th>
        <? endforeach; ?>
    </tr>
    <tr class="filter">
        <th>
            <? Template::render('pages/field_types/field_filter.php',array(
                'field'=>array('name'=>'id'),
                'value'=>htmlspecialchars($_GET['filter']['id']),
                'element'=>false,
                'name'=>'filter[id]',
                'id'=>'filter_id'
            )); ?>
        </th>
        <? foreach($type['fields'] as $i=>$field):?>
            <?
            $field['class']='';
            if($field['name']===$_GET['order']) $field['class'].='order ';
            if(!empty($_GET['desc'])) $field['class'].='desc ';
            ?>
            <th>
                <? Template::render('pages/field_types/field_filter.php',array(
                    'field'=>$field,
                    'value'=>htmlspecialchars($_GET['filter'][$field['name']]),
                    'name'=>'filter['.$field['name'].']',
                    'id'=>'filter_'.$field['name']
                ));
                ?>
            </th>
        <? endforeach; ?>
    </tr>
    </thead>
    <tbody>
        <? foreach($elements as $element): ?>
            <tr>
                <td><input type="checkbox" name="elements[]" value="<?=$element['id']?>"> <?=$element['id']?></td>
            <? foreach($type['fields'] as $i=>$field): $value=htmlspecialchars($element[$field['name']]); ?>
                <td><?=(strlen($value)>100 ? trim(mb_substr($value,0,100)).'...' : $value)?></td>
            <? endforeach; ?>
            </tr>
        <? endforeach; ?>
    </tbody>
    </tr>
</table>
<?
$link="#/type/id/".$type['id'];
if(is_array($_GET['filter'])){
    foreach($_GET['filter'] as $i=>$val) $link.="/filter[".htmlspecialchars($i)."]/".htmlspecialchars($val);
}
if(!empty($_GET['order'])) $link.="/order/".htmlspecialchars($_GET['order']);
?>
<ul class="pagination">
    <li><a href="<?=(int)$_GET['p']-1?>">&laquo;</a></li>
    <? for($i=0; $i<10; $i++):?>
    <li<?=($i==$_GET['p'] ? ' class="active"' : '')?>><a href="<?=$link?>/p/<?=$i?>"><?=($i+1)?></a></li>
    <? endfor;?>
    <li><a href="<?=(int)$_GET['p']+1?>">&raquo;</a></li>
</ul>
<script>
    filter = {
        submit: function() {
            var q='';
            $('table tr.filter').find('input, select').each(function(i){
                if($(this).val()&&$(this).attr('name')) q+='/'+$(this).attr('name')+'/'+$(this).val();
            });
            location.hash='/type/id/<?=(int)$_GET['id']?>'+q;
        }
    };

    if(window.f) {
        var input=$('#'+window.f);
        input[0].selectionStart = input[0].selectionEnd = input.val().length;
    }

    $('table tr.filter').find('input, select').keyup(function(e){
        window.f=$(this).attr('id');
    });

    $('table tr.filter').keyup(function(e){
        clearTimeout(window.timer);
        window.timer = setTimeout(function() { filter.submit() },1000);
        if(e.which==13) {
            filter.submit();
        }
    });

    $('.selectpicker').change(function(){
        filter.submit();
    });
</script>
