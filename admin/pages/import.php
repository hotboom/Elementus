<?
$type_id=(int)$_GET['type'];
$type=E::getType($type_id);
$import=E::getTypeOpt($type['id'],'import');
?>
<? if(!empty($_FILES)):
    $_FILES['file']['name']=substr(md5(time().rand(0,99)),0,20).'.'.substr($_FILES['file']['name'], strrpos($_FILES['file']['name'], '.') + 1);
    $uploaddir = $root_path.'/upload/';
    $uploadfile = $uploaddir . basename($_FILES['file']['name']);


    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) echo $_FILES['file']['name'];
?>
<? elseif(!empty($_POST['step1'])):
    //echo '<pre>'.print_r($_POST).'</pre>';
    //E::debug();
    $result=E::setTypeOpt('import',$_POST['import'],$type['id']);
    //else $result=E::setApp($_POST['app']);
    if($result):
    $file=array('name'=>$_POST['import']['file']);
    if(substr($file['name'], strrpos($file['name'], '.') + 1)==='xml'){
        require($root_path.'/modules/import/xmlparser.php');
        $xml_parser = new xml();

        $xmlfile=$root_path.'upload/f56d2af56e09d54f78c2.xml';
        if (!($fp = fopen($xmlfile, "r"))) die("could not open XML input");

        while ($data = fgets($fp))
        {
            if (!$xml_parser->parse($data,feof($fp))) break;
        }

        $depth=0;
        foreach($xml_parser->tags as $tag){
            if($tag['depth']>$depth) echo '<ul>';
            if($tag['depth']<$depth) echo '</ul>';
            $depth=$tag['depth'];

            echo '<li>'.$tag['name'].'</li>';
        }
    }
    ?>
    <? else:?>
    <div class="alert alert-warning"><i class="fa fa-warning-sign"></i> <?=t('Error occurred:'.E::$error['desc'])?></div>
    <? endif;?>
<? else:?>
    <script>
        $(function() {
            $('.modal-title').html('<?=t($type['name'].' import')?>');
            $('.modal-footer').hide();
        });
    </script>
    <form method="POST" data-async data-target="#window .modal-body" action="/admin/index.php?page=import&type=<?=$type['id']?>" class="form-horizontal">
        <fieldset>
            <div class="form-group">
                <label class="col-lg-2 control-label" for="input_type"><?=t('Import')?></label>
                <div class="col-lg-10">
                    <select id="input_type" class="form-control">
                        <option value="once"><?=t('Once')?></option>
                        <option value="schedule"><?=t('Schedule')?></option>
                    </select>
                </div>
            </div>
            <div class="import_once">
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="input_name"><?=t('File')?></label>
                    <div class="col-lg-10">
                        <input name="import[file]" type="text" class="form-control pull-left clearfix" id="import_file" style="width:auto;" value="<?=$import['file']?>">
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
            <input type="hidden" name="step1" value="submit">
        </fieldset>
    </form>
<? endif;?>