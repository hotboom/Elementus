<?
//Elements::debug();
$type_id=(int)$_GET['id'];
$type=Elements::getTypeById($type_id);

$types=Elements::getFullType($type_id);
$type['fields']=array();
$type['fields']=Elements::getFullTypeFields($type);

$type['class']=Elements::getTypeClass($type['name']);
if(!class_exists($type['class']['name'])) require_once($type['class']['path']);
$params=array('type'=>$type['name']);

$elements=$type['class']['name']::get($params);

?>
    <p class="col-md-8 pull-left">
        <a href="page/element/act/add/type/<?=$type['id']?>" class="btn btn-success" data-target="#window"><i class="icon-plus"></i> <?=t('Add')?></a>
        <a href="/admin/router.php?page=element&type=<?=$type['id']?>&act=edit" class="btn btn-primary disabled" data-target="#window" id="btn-edit"><i class="icon-edit"></i> <?=t('Edit')?></a>
        <a href="page/element/act/copy/type/<?=$type['id']?>" class="btn btn-primary disabled" data-target="#window" id="btn-copy"><i class="icon-copy"></i> <?=t('Copy')?></a>
        <a href="/admin/router.php?page=element&type=<?=$type['id']?>&act=delete" class="btn btn-danger disabled" data-target="#window" id="btn-delete"><i class="icon-remove"></i> <?=t('Delete')?></a>
    </p>
    <p class="col-md-4 pull-right">
        <a href="/admin/router.php?page=type_form&act=edit&type=<?=$type['id']?>" class="btn btn-primary" data-target="#window"><i class="icon-cog"></i> <?=t('Settings')?></a>
        <a href="#/page/type_fields/<?=$type['id']?>" class="btn btn-primary"><i class="icon-cog"></i> <?=t('Fields')?></a>
        <a href="/admin/router.php?page=type_form" class="btn btn-primary" data-target="#window"><i class="icon-plus"></i> <?=t('Add subtype')?></a>
    </p>

<table id="elements" class="table table-hover table-condensed">
    <tr>
        <? foreach($type['fields'] as $i=>$field):?>
            <? if($field['Field']=='element_id'):?>
                <th><a href="#/page/type/id/<?=$type['id']?>/sort/<?=$field['Field']?>">id</a></th>
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
        // Nav links
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

        //Selecting elements
        $('#elements tr').click(function(event){
            var checkbox=$(this).find('input[type=checkbox]');
            checkbox.click();
            event.preventDefault();
        });

        $('#elements tr input[type=checkbox]').click(function(event){
            var tr=$(this).parents('tr');
            tr.toggleClass('active');
            if($('#elements tr.active').size()){
                $('#btn-edit').removeClass('disabled');
                $('#btn-copy').removeClass('disabled');
                $('#btn-delete').removeClass('disabled');
            }
            else{
                $('#btn-edit').addClass('disabled');
                $('#btn-copy').addClass('disabled');
                $('#btn-delete').addClass('disabled');
            }
            event.stopPropagation();
        });
    });
</script>