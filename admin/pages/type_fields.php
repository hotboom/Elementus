<?
//Elements::debug();
$type_id=(int)$_GET['id'];
$type=Elements::getType($type_id);

$types=Elements::getFullType($type_id);
$type['fields']=array();
$type['fields']=Elements::getTypeFields($type);

$type['class']=Elements::getTypeClass($type['name']);
if(!class_exists($type['class']['name'])) require_once($type['class']['path']);
$params=array('type'=>$type['name']);
//print_r($type['fields']);
?>
<p class="col-md-8 pull-left">
    <a href="page/field_form/act/add/type/<?=$type['id']?>" class="btn btn-success" data-target="#window"><i class="icon-plus"></i> <?=t('Add')?></a>
    <a href="/admin/router.php?page=field_form&type=<?=$type['id']?>&act=edit" class="btn btn-primary disabled" data-target="#window" id="btn-edit"><i class="icon-edit"></i> <?=t('Edit')?></a>
    <a href="page/field_form/act/copy/type/<?=$type['id']?>" class="btn btn-primary disabled" data-target="#window" id="btn-copy"><i class="icon-copy"></i> <?=t('Copy')?></a>
    <a href="/admin/router.php?page=element&type=<?=$type['id']?>&act=delete" class="btn btn-danger" data-target="#window"><i class="icon-remove"></i> <?=t('Delete')?></a>
</p>

<p class="col-md-4 pull-right">
    <a href="#/page/type/<?=$type['id']?>" class="btn btn-primary"><i class="icon-arrow-left"></i> <?=t('Back to elements')?></a>
</p>

<table id="elements" class="table table-hover table-condensed">
    <tr>
        <th>#</th>
        <th><?=t('Name')?></th>
        <th><?=t('Type')?></th>
        <th><?=t('Default')?></th>
    </tr>
    <? foreach($type['fields'] as $i=>$field):?>
    <tr>
        <td><input type="checkbox" name="fields[]" value="<?=$field['Field']?>"> <?=$i?></td>
        <td><?=$field['Field']?></td>
        <td><?=$field['Type']?></td>
        <td><?=$field['Default']?></td>
    </tr>
    <? endforeach; ?>
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
            }
            else{
                $('#btn-edit').addClass('disabled');
                $('#btn-copy').addClass('disabled');
            }
            event.stopPropagation();
        });
    });
</script>