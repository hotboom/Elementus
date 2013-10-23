<?
$field=$data['field'];
$element=$data['element'];
?>
<input name="<?=$data['name']?>" type="text" class="form-control pull-left clearfix" id="<?=(empty($data['id']) ? 'input_'.$field['name'] : $data['id'])?>" value="<?=$data['value']?>" style="width:auto;" data-field="<?=$field['name']?>">
<a class="btn btn-success fileupload-button" data-fileupload-action="/admin/index.php?page=element&type=<?=$type['id']?>&act=<?=$act?>" data-fileupload-target="#<?=(empty($data['id']) ? 'input_'.$field['name'] : $data['id'])?>">
    <i class="fa fa-plus"></i>
    <span><?=t('Upload files...')?></span>
</a>
<a class="btn btn-default" data-fileupload-action="/admin/index.php?page=element&type=<?=$type['id']?>&act=<?=$act?>" data-fileupload-target="#<?=(empty($data['id']) ? 'input_'.$field['name'] : $data['id'])?>">
    <i class="fa fa-plus"></i>
    <span><?=t('Select from server...')?></span>
</a>
<div class="clearfix"></div>
<? if(!empty($data['value'])):?>
    <img src="/upload/files/<?=$data['value']?>" style="width:100px; height: auto;">
<? endif;?>
<script language="Javascript">
    $(function(){
        //Form
        var form=$('#fileupload-form');
        if(!form.size()){
            form=$('<form id="fileupload-form" target="fileupload-iframe" method="post" enctype="multipart/form-data" encoding="multipart/form-data" style="display:none;"><input type="file" name="file"></form>');
            $('body').append(form);
        }

        //Fileinput
        var fileinput=form.find('input[name="file"]');
        fileinput.change(function(event){
            form.submit();
        });

        //Iframe
        var iframe=$('#fileupload-iframe');
        if(!iframe.size()){
            iframe=$('<iframe id="fileupload-iframe" name="fileupload-iframe" style="display:none;" />');
            $('body').append(iframe);
        }
        iframe.off('load');
        iframe.off('onload');


        $('.fileupload-button').click(function(event){
            var button=$(this);
            form.attr('action',$(this).attr('data-fileupload-action'));
            var target=$($(this).attr('data-fileupload-target'));
            var file_name='';
            iframe.unbind('load');
            iframe.bind('load',function(event){
                file_name=$(this).contents().find("body").html();
                target.val(file_name);
                <? if($field['type']=='image'):?>
                button.parent().find('img').remove();
                button.parent().append('<img src="/upload/files/'+file_name+'" style="width:100px; height: auto;">');
                <? endif;?>
            });
            fileinput.click();
            event.preventDefault();
        });
    });
</script>