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
                button.parent().find('img').remove();
                button.parent().append('<img src="/upload/files/'+file_name+'" style="width:100px; height: auto;">');
            });
            fileinput.click();
            event.preventDefault();
        });
    });