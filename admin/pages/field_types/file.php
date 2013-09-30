<div class="form-group">
    <label class="col-lg-2 control-label" for="input<?=$field['name']?>"><?=t($field['name'])?></label>
    <div class="col-lg-10">
    <input name="fields[<?=$field['name']?>]" type="text" class="form-control pull-left clearfix" id="input<?=$field['name']?>" value="<?=$element[$field['name']]?>" style="width:auto;">
    <a class="btn btn-success fileupload-button" data-fileupload-action="/admin/index.php?page=element&type=<?=$type['id']?>&act=<?=$act?>" data-fileupload-target="#input<?=$field['name']?>">
        <i class="icon-plus"></i>
        <span>Select files...</span>
    </a>
    </div>
</div>
<script language="Javascript">
    $(function(){
        //Form
        var form=$('#fileupload-form');
        if(!form.size()){
            var form=$('<form id="fileupload-form" target="fileupload-iframe" method="post" enctype="multipart/form-data" encoding="multipart/form-data"><input type="file" name="file"></form>');
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
            var iframe=$('<iframe id="fileupload-iframe" name="fileupload-iframe" />');
            $('body').append(iframe);
        }
        iframe.off('load');
        iframe.off('onload');


        $('.fileupload-button').click(function(event){
            //console.log(fileinput);
            form.attr('action',$(this).attr('data-fileupload-action'));
            var target=$($(this).attr('data-fileupload-target'));
            iframe.bind('load',function(event){
                target.val($(this).contents().find("body").html());
            });
            fileinput.click();
            event.preventDefault();
        });
    });
</script>