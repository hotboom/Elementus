<?
//E::debug();
$type_id=(int)$_GET['type'];
$type=E::getType($type_id);
?>
<? if(!empty($_FILES)):
    $_FILES['file']['name']=substr(md5(time().rand(0,99)),0,20).'.'.substr($_FILES['file']['name'], strrpos($_FILES['file']['name'], '.') + 1);
    $uploaddir = $root_path.'/upload/';
    $uploadfile = $uploaddir . basename($_FILES['file']['name']);


    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) echo $_FILES['file']['name'];
?>
<? elseif(!empty($_POST['submit'])):
    //echo '<pre>'.print_r($_POST['fields']).'</pre>';
    //E::debug();
    //if($act=='delete') $result=E::deleteApp($_POST['app']);
    //else $result=E::setApp($_POST['app']);
    ?>
    <? if($result):?>
    <div class="alert alert-success"><i class="fa fa-ok"></i> <?=t('Field succesfuly '.$act)?></div>
    <? else:?>
    <div class="alert alert-warning"><i class="fa fa-warning-sign"></i> <?=t('Error occurred:'.E::$error['desc'])?></div>
    <? endif;?>
    <a href="#/app_form/act/edit/id/<?=(int)$_POST['app']['id']?>/form" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?=t('Back to form')?></a>
<? else:?>
    <script>
        $(function() {
            $('.modal-title').html('<?=t($type['name'].' import')?>');
            $('.modal-footer').hide();
        });
    </script>
    <form method="POST" data-async data-target="#page" action="/admin/index.php?page=app_form&act=<?=$act?>" class="form-horizontal">
        <fieldset>
            <input name="import[type]" type="hidden" value="<?=$type['id']?>">
            <div class="form-group">
                <label class="col-lg-2 control-label" for="input_type"><?=t('Import')?></label>
                <div class="col-lg-10">
                    <select name="app[template_id]" id="input_type" class="form-control">
                        <option value=""><?=t('Once')?></option>
                        <option value=""><?=t('Schedule')?></option>
                    </select>
                </div>
            </div>
            <div class="impotr_once">
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="input_name"><?=t('File')?></label>
                    <div class="col-lg-10">
                        <input name="import_file" type="text" class="form-control pull-left clearfix" id="import_file" style="width:auto;" data-field="<?=$field['name']?>">
                        <a class="btn btn-success fileupload-button" data-fileupload-action="/admin/index.php?page=import&type=<?=$type['id']?>" data-fileupload-target="#import_file">
                            <i class="fa fa-plus"></i>
                            <span><?=t('Upload files...')?></span>
                        </a>
                        <a class="btn btn-default" data-fileupload-target="#import_file">
                            <i class="fa fa-plus"></i>
                            <span><?=t('Select from server...')?></span>
                        </a>
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
                                    });
                                    fileinput.click();
                                    event.preventDefault();
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <div class="col-lg-10">
                    <button type="submit" class="btn btn-success"><?=t('Save')?></button>
                    <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
                </div>
            </div>
            <input type="hidden" name="submit" value="submit">
        </fieldset>
    </form>
<? endif;?>