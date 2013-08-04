<? require_once("../core/init.php"); ?>
<?
$type_id=(int)$_GET['id'];
$type=Elements::getTypeById($type_id);
$types=Elements::getFullType($type_id);
$type['fields']=array();
foreach($types as $i=>$val){
    $types[$i]['fields']=Elements::getTypeFields($types[$i]);
    $type['fields']=array_merge($type['fields'],$types[$i]['fields']);
}
$type['class']=Elements::getTypeClass($type['name']);
if(!class_exists($type['class']['name'])) require_once($type['class']['path']);
if($type['class']['name']=='Elements') $params=array('type'=>$type['name']);
else $params=array();
$elements=$type['class']['name']::get($params);

?>
<!--<pre>-->
<?// //print_r($type['fields']); ?>
<?// //print_r($elements); ?>
<!--</pre>-->
<p>
    <a href="page/element/act/add/type/<?=$type['id']?>" class="btn btn-success" data-target="#window"><?=t('Add')?></a>
    <a href="page/element/act/copy/type/<?=$type['id']?>" class="btn btn-primary" data-target="#window"><?=t('Copy')?></a>
    <a href="page/element/act/delete/type/<?=$type['id']?>" class="btn btn-danger" data-target="#window"><?=t('Delete')?></a>
</p>
<table id="elements" class="table table-hover table-condensed">
    <tr>
        <? foreach($type['fields'] as $i=>$field):?>
            <? if($field['Field']=='element_id'):?>
                <th class="span1"><a href="#/page/type/id/<?=$type['id']?>/sort/<?=$field['Field']?>">id</a></th>
            <? else:?>
                <th><a href="#/page/type/id/<?=$type['id']?>/sort/<?=$field['Field']?>"><?=t($field['Field'])?></a></th>
            <?endif;?>
        <? endforeach; ?>
        <? foreach($elements as $element): ?>
            <tr>
            <? foreach($type['fields'] as $i=>$field):?>
                <? if($field['Field']=='element_id'):?>
                <td><input type="checkbox" name="elements[]" value="<?=$element[$field['Field']]?>"> <?=$element[$field['Field']]?></td>
                <? else:?>
                <td><?=$element[$field['Field']]?></td>
                <? endif;?>
            <? endforeach; ?>
            </tr>
        <? endforeach; ?>
    </tr>
</table>
<script>
    $(function() {
        $('a[data-target]').click(function(event) {
            target=$(this).attr('data-target');
            url=$(this).attr('href')+'?';
            $('#elements input:checked').each(function(){
                url=url+$(this).attr('name')+'='+$(this).val()+'&';
            });

            $.get(url, function(data) {
                $(target+' .modal-body').html(data);
            });
            $(target).modal('show');
            event.preventDefault();
        });

//        $('#elements input[type=checkbox]').click(function(event){
//            if($(this).attr('checked')) $(this).parents('tr').removeClass('success');
//            else $(this).parents('tr').addClass('success');
//            event.stopPropagation();
//            //event.preventDefault();
//        });
        $('#elements tr').click(function(event){
            if($(this).find('input[type=checkbox]').attr('checked')) {
                $(this).find('input[type=checkbox]').attr('checked',false);
                $(this).removeClass('success');
            }
            else{
                $(this).find('input[type=checkbox]').attr('checked',true);
                $(this).addClass('success');
            }
            event.preventDefault();
        });
    });
</script>