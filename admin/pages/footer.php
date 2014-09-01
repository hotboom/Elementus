<!-- Modal -->
<div class="modal fade" id="window" role="dialog" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-inner">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="btn-group btn-group-sm pull-right">
                        <a href="#" class="btn btn-default fa fa-minus" onclick="$('#window').removeClass('fullscreen'); $('#window').toggleClass('minimizated'); return false;"></a>
                        <a href="#" class="btn btn-default fa fa-fullscreen" onclick="$('#window').toggleClass('fullscreen'); return false;"></a>
                        <a href="#" class="btn btn-danger fa fa-times"  data-dismiss="modal" aria-hidden="true"></a>
                    </div>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div id="modal-minimize">
                    <div class="modal-body">
                        <?=t('Loading').'...'?>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Close')?></a>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-body -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="lang_form" style="display: none;">
    <form class="form-inline" role="form">
        <div class="form-group">
            <label class="sr-only" for="input_en">En:</label>
            <input type="email" class="form-control" name="en" id="input_en" placeholder="en">
        </div>
        <div class="form-group">
            <label class="sr-only" for="input_ru">Ru:</label>
            <input type="email" class="form-control" name="ru" id="input_ru" placeholder="ru">
        </div>
        <button type="submit" class="btn btn-default"><?=t('Save')?></button>
    </form>
</div>
</body>
</html>