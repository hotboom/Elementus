<? if(!empty($_POST['submit'])):
    //print_r( $_POST);
    $command=str_replace('\"','"',$_POST['command']);
    $command=str_replace("\'","'",$_POST['command']);
    eval($command.';');
else:?>
<form method="POST" data-async data-target="#console_log" action="/admin/index.php?page=console" class="form-horizontal">
    <fieldset>
        <input type="hidden" name="submit" value="submit">
        <div class="form-group">
            <div class="col-lg-12" id="console_log"></div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label" for="console">Command</label>
            <div class="col-lg-12">
                <textarea name="command" id="command" class="form-control" rows="6" placeholder="Enter command..." style="width:100%;"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label"></label>
            <div class="col-lg-12">
                <button type="submit" class="btn btn-success"><?=t('Execute')?></button>
                <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Cancel')?></a>
            </div>
        </div>
        <input type="hidden" name="fields[type]" value="<?=$type['id']?>">
        <input type="hidden" name="submit" value="submit">
    </fieldset>
</form>
<? endif;?>