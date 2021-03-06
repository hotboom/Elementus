<?
//E::debug();
if(!empty($_GET['id'])) $type=(int)$_GET['id'];

if(!$type=E::getType($type)) {
    foreach(E::$errors as $error) echo $error['desc'].'<br>';
    exit;
}

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
if(!empty($_GET['search'])) {
    $params['filter']='';
    if(is_array($_GET['search'])) {
        $i=0;
        foreach($_GET['search'] as $name=>$val) {
            $params['filter'].=($i!=0 ? 'AND ': '')."CONCAT(' ',`".$name."`) LIKE '%".$val."%' ";
            $i++;
        }
    }
}
if(!empty($_GET['filter'])) {
    if(is_array($_GET['filter'])) {
        foreach($_GET['filter'] as $i=>$v) $_GET['filter'][$i]=empty($v) ? false : $v;
        $params['filter']=$_GET['filter'];
    }
}
if(!empty($_GET['limit'])) $params['limit']=(int)$_GET['limit'];
if(empty($_GET['hide'])) $_GET['hide']=array();
if(empty($_GET['fields'])) $_GET['fields']=array();
if(empty($_GET['target'])) $target='.modal-body';
else $target=htmlspecialchars($_GET['target']);

$count=$type['class']['name']::count($params);
$elements=$type['class']['name']::get($params);

foreach($type['fields'] as $field){
    if($field['type']=='elements') {
        $subtype['class']=E::getTypeClass($field['elements_type']);
        foreach($elements as $i=>$element) {
            $el=E::getById($elements[$i][$field['name']]);
            if(!empty($el['name'])) $elements[$i][$field['name']]=$el['name'];
        }
    }
}
$options='';
?>
<div class="row elements-menu">
<? if($_GET['buttons']!=='no'):?>
<?
    if(!empty($_GET['hide'])) {
        foreach($_GET['hide'] as $v) $options.='&hide[]='.$v;
    }
    if(!empty($_GET['fields'])){
        foreach($_GET['fields'] as $i=>$v) $options.='&fields['.$i.']='.$v;
    }
    if(!empty($target)) $options.='&target='.urlencode($target);
    ?>
    <p class="pull-left">
        <a href="element/act/add/type/<?=$type['id']?>?<?=$options?>" class="btn btn-success" data-target="<?=$target?>" tabindex="1"><i class="fa fa-plus"></i> <?=t('Add')?></a>
        <a href="/admin/index.php?page=element&type=<?=$type['id']?>&act=edit" class="btn btn-primary disabled" data-target="<?=$target?>" id="btn-edit" tabindex="2"><i class="fa fa-edit"></i> <?=t('Edit')?></a>
        <a href="/admin/index.php?page=element&type=<?=$type['id']?>&act=copy" class="btn btn-primary disabled" data-target="<?=$target?>" id="btn-copy" tabindex="3"><i class="fa fa-copy"></i> <?=t('Copy')?></a>
        <a href="/admin/index.php?page=element&type=<?=$type['id']?>&act=delete" class="btn btn-danger disabled" data-target="<?=$target?>" id="btn-delete" tabindex="4"><i class="fa fa-times"></i> <?=t('Delete')?></a>
    </p>
<? endif;?>
<? if($_GET['advanced']!=='no'):?>
<div class="dropdown pull-right" style="margin:0 3px;">
    <a data-toggle="dropdown" href="#" class="btn btn-primary">Edit&nbsp;<div class="caret"></div></a>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
        <li role="presentation"><a role="menuitem" href="replace/type/<?=$type['id']?>" data-target="<?=$target?>"><i class="fa fa-search"></i> <?=t('Find and replace')?></a></li>
        <li role="presentation"><a role="menuitem" href="import/type/<?=$type['id']?>" data-target="<?=$target?>"><i class="fa fa-reply-all"></i> <?=t('Import')?></a></li>
        <li role="presentation"><a role="menuitem" href="type_clear/type/<?=$type['id']?>" data-target="<?=$target?>"><i class="fa fa-times"></i> <?=t('Clear')?></a></li>
    </ul>
</div>
<p class="pull-right">
    <a href="/admin/index.php?page=type_form&act=edit&type=<?=$type['id']?>" class="btn btn-primary" data-target="<?=$target?>"><i class="fa fa-cog"></i> <?=t('Settings')?></a>
    <a href="#/type_fields/id/<?=$type['id']?>" class="btn btn-primary"><i class="fa fa-cog"></i> <?=t('Fields')?></a>
    <a href="/admin/index.php?page=type_form&parent=<?=$type['id']?>" class="btn btn-primary" data-target="<?=$target?>"><i class="fa fa-plus"></i> <?=t('Add subtype')?></a>
</p>
<? endif; ?>
</div>

<table class="table table-hover table-condensed selectable">
    <thead>
    <tr>
        <th><a href="#/type/id/<?=$type['id']?>/order/<?=$field['name']?>">id</a></th>
        <? foreach($type['fields'] as $i=>$field):?>
            <?
            if(in_array($field['name'],$_GET['hide'])) continue;
            $field['class']='';
            if($field['name']===$_GET['order']) $field['class'].='order ';
            if(!empty($_GET['desc'])) $field['class'].='desc ';
            ?>
            <th><a href="#/type/id/<?=$type['id']?>/order/<?=$field['name']?><?=(empty($_GET['desc']) ? '/desc/1' :'')?>"<?=(!empty($field['class']) ? ' class="'.trim($field['class']).'"' :'')?>><?=t($field['name'])?></a></th>
        <? endforeach; ?>
    </tr>
    <? if($_GET['filters']!=='no'):?>
    <tr class="filter">
        <th>
            <? Template::render('pages/field_types/field_filter.php',array(
                'field'=>array('name'=>'id'),
                'value'=>htmlspecialchars($_GET['search']['id']),
                'element'=>false,
                'name'=>'search[id]',
                'id'=>'filter_id'
            )); ?>
        </th>
        <? foreach($type['fields'] as $i=>$field):?>
            <?
            if(in_array($field['name'],$_GET['hide'])) continue;
            $field['class']='';
            if($field['name']===$_GET['order']) $field['class'].='order ';
            if(!empty($_GET['desc'])) $field['class'].='desc ';
            ?>
            <th>
                <? Template::render('pages/field_types/field_filter.php',array(
                    'field'=>$field,
                    'value'=>htmlspecialchars($_GET['search'][$field['name']]),
                    'name'=>'search['.$field['name'].']',
                    'id'=>'search_'.$field['name']
                ));
                ?>
            </th>
        <? endforeach; ?>
    </tr>
    <? endif;?>
    </thead>
    <tbody>
        <? foreach($elements as $element): ?>
            <tr>
                <td><input type="checkbox" name="elements[]" value="<?=$element['id']?>"> <?=$element['id']?></td>
            <? foreach($type['fields'] as $i=>$field): $value=htmlspecialchars(is_array($element[$field['name']]) ? '' : $element[$field['name']]); ?>
                <? if(in_array($field['name'],$_GET['hide'])) continue; ?>
                <td><?=(strlen($value)>100 ? trim(mb_substr($value,0,100)).'...' : $value)?></td>
            <? endforeach; ?>
            </tr>
        <? endforeach; ?>
    </tbody>
    </tr>
</table>
<?
$link="#/type/id/".$type['id'];
if(is_array($_GET['search'])){
    foreach($_GET['search'] as $i=>$val) $link.="/search[".htmlspecialchars($i)."]/".htmlspecialchars($val);
}
if(!empty($_GET['order'])) $link.="/order/".htmlspecialchars($_GET['order']);
?>
<?
$next=(int)$_GET['p']+1;
$prev=(int)$_GET['p']-1;
?>
<ul class="pagination">
    <?if($prev>=0):?>
    <li><a href="<?=$link?>/p/<?=$prev?>">&laquo;</a></li>
    <?endif;?>
    <? for($i=0; $i<ceil($count/30); $i++):?>
    <li<?=($i==$_GET['p'] ? ' class="active"' : '')?>><a href="<?=$link?>/p/<?=$i?>"><?=($i+1)?></a></li>
    <? endfor;?>
    <?if($next<$count/30):?>
    <li><a href="<?=$link?>/p/<?=$next?>">&raquo;</a></li>
    <?endif;?>
</ul>
<script>
    window.type=<?=(int)$_GET['id']?>;

    filter = {
        submit: function() {
            var q='';
            $('table tr.filter').find('input, select').each(function(i){
                if($(this).val()&&$(this).attr('name')) q+='/'+$(this).attr('name')+'/'+$(this).val();
            });
            location.hash='/type/id/'+window.type+q;
        }
    };

    if(window.f) {
        var input=$('#'+window.f);
        if(input.val()) input[0].selectionStart = input[0].selectionEnd = input.val().length;
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

    $('table tr.filter .selectpicker').change(function(){
        filter.submit();
    });
</script>
