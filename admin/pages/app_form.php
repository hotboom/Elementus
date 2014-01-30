<?
if(!empty($_GET['act'])) $act=htmlspecialchars($_GET['act']);
else $act='edit';

if($act=='edit'|$act=='copy') {
    $app_id=(int)$_GET['id'];
    $filter='id='.$app_id;
    $apps=E::getApps(array('filter'=>$filter));
    $app=$apps[0];

}
else $app=array();
?>

<? if(!empty($_POST['submit'])):
    //echo '<pre>'.print_r($_POST['fields']).'</pre>';
    //E::debug();
    if($act=='delete') $result=E::deleteApp($_POST['app']);
    else $result=E::setApp($_POST['app']);
    ?>
    <? if($result):?>
        <div class="alert alert-success"><i class="fa fa-ok"></i> <?=t('Field succesfuly '.$act)?></div>
    <? else:?>
        <div class="alert alert-warning"><i class="fa fa-warning-sign"></i> <?=t('Error occurred:')?><? foreach(E::$errors['desc'] as $error): echo $error['descr']; endforeach;?></div>
    <? endif;?>
    <a href="#/app_form/act/edit/id/<?=(int)$_POST['app']['id']?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?=t('Back to form')?></a>
<? else:?>
    <script>
        $(function() {
            $('.modal-title').html('<?=t($act.' field')?>');
            $('.modal-footer').hide();
        });
    </script>
    <form method="POST" data-async data-target="#page" action="/admin/index.php?page=app_form&act=<?=$act?>">
        <? if($act=='delete'):?>
            <p><?=t('delete selected fields')?>?</p>
            <? if(is_array($_GET['fields'])):?>
                <? foreach($_GET['fields'] as $i=>$val):?>
                    <input type="hidden" name="fields[]" value="<?=$val?>">
                <? endforeach;?>
            <?endif?>
            <button type="submit" class="btn btn-success"><?=t('Delete')?></button>
            <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
            <input type="hidden" name="submit" value="submit">
        <?else:?>
            <fieldset>
                <input name="app[id]" type="hidden" value="<?=($act!='copy' ? $app['id']:'')?>">
                <div class="form-group">
                    <label for="input_name"><?=t('Name')?></label>
                    <input name="app[name]" type="text" class="form-control" id="input_name" value="<?=$app['name']?>">
                </div>
                <div class="form-group">
                    <label for="input_domain"><?=t('Domain')?></label>
                    <input name="app[domain]" type="text" class="form-control" id="input_domain" value="<?=$app['domain']?>">
                </div>
                <div class="form-group" id="group_view_fields">
                    <label for="input_alias"><?=t('Alias')?></label>
                    <textarea class="form-control" rows="3" name="app[alias]" id="input_alias"><?=$app['alias']?></textarea>
                </div>
                <div class="form-group">
                    <label for="input_type"><?=t('Template')?></label>
                    <select name="app[template_id]" id="input_type" class="form-control">
                        <option value="INT(11)"><?=t('Not set')?></option>
                        <? $templates=E::get(array('type'=>'templates'));
                        foreach($templates as $template):?>
                            <option value="<?=$template['id']?>"<?=($app['template_id']==$template['id'] ? ' selected="selected"' : '')?>><?=$template['name']?></option>
                        <? endforeach;?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success"><?=t($act)?></button>
                <a href="#/dashboard" class="btn btn-default"><?=t('Cancel')?></a>
                <input type="hidden" name="fields[type]" value="<?=$type['id']?>">
                <input type="hidden" name="submit" value="submit">
            </fieldset>
        <?endif;?>
    </form>
<? endif;?>