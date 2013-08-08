<?
$type_id=(int)$_GET['id'];
$type=Elements::getTypeById($type_id);
//Elements::debug();
$types=Elements::getFullType($type_id);
$type['fields']=array();
$type['fields']=Elements::getTypeFields($type);

$type['class']=Elements::getTypeClass($type['name']);
if(!class_exists($type['class']['name'])) require_once($type['class']['path']);
$params=array('type'=>$type['name']);

//Elements::debug();
$elements=$type['class']['name']::get($params);

?>
<p>
    <a href="page/element/act/add/type/<?=$type['id']?>" class="btn btn-success" data-target="#window"><?=t('Add')?></a>
    <a href="/admin/router.php?page=element&type=<?=$type['id']?>&act=edit" class="btn btn-primary" data-target="#window"><?=t('Edit')?></a>
    <a href="page/element/act/copy/type/<?=$type['id']?>" class="btn btn-primary" data-target="#window"><?=t('Copy')?></a>
    <a href="/admin/router.php?page=element&type=<?=$type['id']?>&act=delete" class="btn btn-danger" data-target="#window"><?=t('Delete')?></a>
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
            url=$(this).attr('href');
            $('#elements input:checked').each(function(){
                url=url+'&'+$(this).attr('name')+'='+$(this).val();
            });
            //alert(url);
            $.get(url, function(data) {
                $(target+' .modal-body').html(data);
            });
            $(target).modal('show');
            event.preventDefault();
        });

        $('#elements tr').click(function(event){
            var checkbox=$(this).find('input[type=checkbox]');
            checkbox.click();
            event.preventDefault();
        });

        $('#elements tr input[type=checkbox]').click(function(event){
            var tr=$(this).parents('tr');
            if(tr.hasClass('success')) tr.removeClass('success');
            else tr.addClass('success');
            event.stopPropagation();
        });
    });
</script>