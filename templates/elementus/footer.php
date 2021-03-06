</div> <!-- /container -->
<!-- Modal -->
<div class="modal fade" id="window">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal"><?=t('Close')?></a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>

    $(function() {
        $('form[data-async]').submit(function(event) {
            var form = $(this);
            var target = $(form.attr('data-target'));
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),

                success: function(data, status) {
                    window.location.href='/';
                },
                error: function(data) {
                    $('.modal-title').html('<?=t('Error')?>');
                    $(target).html('<?=t('Wrong username or password')?>');
                    $('#window').modal('show');
                }

            });
            event.preventDefault();
        });
    });
</script>
</body>
</html>