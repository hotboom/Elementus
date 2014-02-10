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
        $file['path']=$root_path.'upload/'.$file['name'];

        if($_POST['import']['format']=='yml'){
            if(substr($file['name'], strrpos($file['name'], '.') + 1)==='xml'|substr($file['name'], strrpos($file['name'], '.') + 1)==='yml'){
                require($root_path.'/modules/import/xmlparser.php');
                set_time_limit(360);
                $xml_parser = new xml();
                $xml_parser->processor='process';
                //E::debug();
                E::clearType(20);
                E::clearType(21);
                $offer=array();
                function process($tag){
                    global $offer;
                    if($tag['name']=='CATEGORY'){
                        E::set(array(
                            'type'=>21,
                            'id1c'=>(int)$tag['attr']['ID'],
                            'parentId'=>(int)$tag['attr']['PARENTID'],
                            'name'=>$tag['value']
                        ));
                    }
                    if($tag['name']=='OFFER')  {
                        if(!empty($offer)){
                            E::set(array(
                                'type'=>20,
                                'id1c'=>$offer['id'],
                                'categoryId'=>$offer['category'],
                                'model'=>$offer['model'],
                                'price'=>$offer['price'],
                                'store'=>$offer['store']
                            ));
                            $offer=array();
                        }
                        $offer['id']=(int)$tag['attr']['ID'];
                    }
                    if($tag['name']=='CATEGORYID') $offer['category']=$tag['value'];
                    if($tag['name']=='MODEL') $offer['model']=$tag['value'];
                    if($tag['name']=='PRICE') $offer['price']=$tag['value'];
                    if($tag['name']=='STORE') $offer['store']=$tag['value'];
                }


                $xmlfile=$file['path'];

                if (!($fp = fopen($xmlfile, "r"))) die("could not open XML input");

                while ($data = fgets($fp))
                {
                    if (!$xml_parser->parse($data,feof($fp))) break;
                }

                $depth=0;
                /*foreach($xml_parser->tags as $tag){
                    if($tag['depth']>$depth) echo '<ul>';
                    if($tag['depth']<$depth) echo '</ul>';
                    $depth=$tag['depth'];

                    echo '<li>'.$tag['name'].'</li>';
                }*/
            }
        }
        elseif($_POST['import']['format']=='excelxml'){
            require($root_path.'/modules/import/excel_xml_parser.php');
            //E::clearType($type_id);
            $xml_parser = new xml();
            if (!($fp = fopen($file['path'], "r"))) die("could not open XML input");
            while ($data = fgets($fp)) if (!$xml_parser->parse($data,feof($fp))) break;
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
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="input_encoding"><?=t('Encoding')?></label>
                    <div class="col-lg-10">
                        <select name="import[encoding]" id="input_encoding" class="form-control selectpicker">
                            <option value="utf-8">UTF-8</option>
                            <option value="ansi">ANSI</option>
                            <option value="cp1251">WINDOWS-1251</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="input_format"><?=t('Encoding')?></label>
                    <div class="col-lg-10">
                        <select name="import[format]" id="input_format" class="form-control selectpicker">
                            <option value="yml">YML</option>
                            <option value="excelxml">Excel 2003 XML</option>
                        </select>
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
<? endif;?>