<?
$field=$data['field'];
$element=$data['element'];
$id=(empty($data['id']) ? 'input_'.$field['name'] : $data['id']);
?>
<input name="<?=$data['name']?>" type="text" class="form-control pull-left clearfix" id="<?=$id?>" value="<?=$data['value']?>" style="width:auto;" data-field="<?=$field['name']?>">
<a class="btn btn-success fileupload-button" data-fileupload-action="/admin/index.php?page=element&type=<?=$type['id']?>&act=<?=$act?>" data-fileupload-target="#<?=(empty($data['id']) ? 'input_'.$field['name'] : $data['id'])?>">
    <i class="fa fa-plus"></i>
    <span><?=t('Upload files...')?></span>
</a>
<a class="btn btn-default" onclick="openKCFinder('#<?=$id?>');">
    <i class="fa fa-plus"></i>
    <span><?=t('Select from server...')?></span>
</a>
<div class="clearfix"></div>
<? if(!empty($data['value'])):?>
<div class="row"><div class="col-sm-6 col-md-3"><a href="#" class="thumbnail"><img src="/upload/files/<?=$data['value']?>" class="img-responsive"></a></div></div>
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
                button.parent().append('<div class="row"><div class="col-sm-6 col-md-3"><a href="#" class="thumbnail"><img src="/upload/files/'+file_name+'" class="img-responsive"></a></div></div>');
                <? endif;?>
            });
            fileinput.click();
            event.preventDefault();
        });
    });
    function openKCFinder(field) {
        window.KCFinder = {
            callBack: function(url) {
                var filename=url.substring(url.lastIndexOf('/')+1);
                $(field).val(filename);
                window.KCFinder = null;
            }
        };
        window.open('/admin/plugins/kcfinder/browse.php?type=Files', 'kcfinder_textbox',
            'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
                'resizable=1, scrollbars=0, width=800, height=600'
        );
    }
</script>