<div class="form-group">
    <label class="col-lg-2 control-label" for="input<?=$field['name']?>"><?=t($field['name'])?></label>
    <div class="col-lg-10">
        <input name="fields[<?=$field['name']?>]" type="text" class="form-control pull-left clearfix" id="input<?=$field['name']?>" value="<?=$element[$field['name']]?>" style="width:auto;">
        <a class="btn btn-success fileupload-button" data-fileupload-action="/admin/index.php?page=element&type=<?=$type['id']?>&act=<?=$act?>" data-fileupload-target="#input<?=$field['name']?>">
            <i class="icon-plus"></i>
            <span><?=t('Upload files...')?></span>
        </a>
        <a class="btn btn-default" data-fileupload-action="/admin/index.php?page=element&type=<?=$type['id']?>&act=<?=$act?>" data-fileupload-target="#input<?=$field['name']?>">
            <i class="icon-plus"></i>
            <span><?=t('Select from server...')?></span>
        </a>
        <div class="clearfix"></div>
    </div>
</div>
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
                button.parent().append('<img src="/upload/files/'+file_name+'" width="100" height="100">');
                <? endif;?>
            });
            fileinput.click();
            event.preventDefault();
        });
    });
</script>