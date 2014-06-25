<?
$type_id=(int)$_GET['type'];
$type=E::getType($type_id);

if(!empty($_POST['submit'])){
    echo 'test';
    if(E::clearType($type['id'])) echo t('Clear successful');
}
else{
    ?>
    <form method="POST" data-async data-target="#window .modal-body" action="/admin/index.php?page=type_clear&type=<?=$type['id']?>" class="form-horizontal">
        <fieldset>
            <div class="alert alert-info"><strong><?=t('This action delete all elements in type')?></strong>: <?=t('are you sure?')?></div>
            <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <div class="col-lg-10">
                    <button type="submit" class="btn btn-success"><?=t('Clear type')?></button>
                    <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
                </div>
            </div>
            <input type="hidden" name="submit" value="submit">
        </fieldset>
    </form>
<? } ?>