<!-- Modal -->
<div class="modal fade" id="window" tabindex="-1" role="dialog" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-inner">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="btn-group btn-group-sm pull-right">
                        <a href="#" class="btn btn-default icon-minus" onclick="$('#window').removeClass('fullscreen'); $('#window').toggleClass('minimizated'); return false;"></a>
                        <a href="#" class="btn btn-default icon-fullscreen" onclick="$('#window').toggleClass('fullscreen'); return false;"></a>
                        <a href="#" class="btn btn-danger icon-remove"  data-dismiss="modal" aria-hidden="true"></a>
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
<script>
    $(document).bind("ajaxComplete", function(){

        //Ajax form submit
        $('form[data-async]').unbind().submit(function(event) {
            var form = $(this);
            var target = $(form.attr('data-target'));
            console.log(form.serialize());
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),

                success: function(data, status) {
                    target.html(data);
                }
            });
            event.preventDefault();
        });

        //Ajax links
        $('a[data-target]').unbind().click(function(event){
            target=$(this).attr('data-target');
            url=$(this).attr('href');
            $('table.selectable input:checked').each(function(){
                url=url+'&'+$(this).attr('name')+'='+$(this).val();
            });
            $.ajax({
                url: url,
                cache:false,
                success: function(data){
                    $(target+' .modal-body > *').remove();
                    $(target+' .modal-body').html(data);
                    $(target).modal('show');
                }
            });
            event.preventDefault();
        });

        $('table.selectable tbody').unbind().on('click','tr', function(event){
            var checkbox=$(this).find('input[type=checkbox]');
            checkbox.click();
            event.preventDefault();
        });

        $('table.selectable tbody').on('click','tr input[type=checkbox]',function(event){
            //console.log($(this));
            var tr=$(this).parents('tr');
            tr.toggleClass('active');
            if($('table.selectable tr.active').size()){
                $('#btn-edit').removeClass('disabled');
                $('#btn-copy').removeClass('disabled');
                $('#btn-delete').removeClass('disabled');
            }
            else{
                $('#btn-edit').addClass('disabled');
                $('#btn-copy').addClass('disabled');
                $('#btn-delete').addClass('disabled');
            }
            event.stopPropagation();
        });
    });
</script>
</body>
</html>