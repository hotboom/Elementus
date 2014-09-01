$(document).bind("ajaxComplete", function () {
    //Ajax form submit
    $('form[data-async]').unbind().submit(function (event) {
        var form = $(this);
        var target = $(form.attr('data-target'));

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            beforeSend: function (xhr) {
                target.find('*').hide();
                target.addClass('loading');
            },
            success: function (data, status) {
                target.removeClass('loading');
                target.html(data);
            }
        });
        event.preventDefault();
    });

    var table = $('table.selectable tbody');

    table.unbind().on('click', 'tr', function (event) {
        var checkbox = $(this).find('input[type=checkbox]');
        checkbox.click();
        event.preventDefault();
    });

    table.on('click', 'tr input[type=checkbox]', function (event) {
        //console.log($(this));
        var tr = $(this).parents('tr');
        tr.toggleClass('active');
        if ($('table.selectable tr.active').size()) {
            $('#btn-edit').removeClass('disabled');
            $('#btn-copy').removeClass('disabled');
            $('#btn-delete').removeClass('disabled');
        }
        else {
            $('#btn-edit').addClass('disabled');
            $('#btn-copy').addClass('disabled');
            $('#btn-delete').addClass('disabled');
        }
        event.stopPropagation();
    });

    table.on('dblclick', 'tr', function (event) {
        $('table.selectable input[type=checkbox]').attr('checked', false).parents('tr.active').toggleClass('active');
        var checkbox = $(this).find('input[type=checkbox]');
        checkbox.click();
        $('#btn-edit').click();
        event.preventDefault();
        event.stopPropagation();
    });

    $("table tr.filter th input[type='text']").keypress(function (e) {
        if (e.which == 13) {
            console.log(location.hash);
            location.hash = '#/type/id/7';
            e.preventDefault();
        }

    });

    $('#selectAll').click(function (e) {
        table.find('input[type=checkbox]').click();
    });

    $('.selectpicker').selectpicker({});
    //console.log($('.selectpicker').selectpicker);
    //$('.selectpicker').selectpicker('destroy');
    //$('.selectpicker').selectpicker('refresh');

    //Add field button
    $(".addField").unbind().click(function (e) {
        var num = $('.' + this.dataset.clone).length;
        var group = $('.' + this.dataset.clone + ':last');
        var clone = group.clone();
        clone.find('.bootstrap-select').remove();
        clone.find('input,textarea,select').val('');
        clone.find('.thumbnail').remove();
        var name = clone.find('[name]').attr('name').replace(/\[[0-9]\]/g, '[' + num + ']');
        clone.find('[name]').attr('name', name);

        group.after(clone[0]);
        $(document).trigger('ajaxComplete');
        e.preventDefault();
    });

    //Preview for images
    var preview = $('a[data-hover="popover"]');
    preview.popover({
        html: true,
        content: function () {
            return '<img src="' + this.href + '" style="width:140px; height:auto;">';
        }
    });
    //preview.popover('show');
    preview.hover(function () {
        $(this).popover('show')
    }, function () {
        $(this).popover('hide')
    });

    //Lang menu
    var lang = $('span.lang');
    lang.popover({
        html: true,
        container: 'body',
        content: function () {
            return $('#lang_form').html();
        }
    });
    //preview.popover('show');
    lang.hover(function () {
            $(this).popover('show');
        },
        function () {
            if ($('.popover:hover').length == 0) {
                $(this).popover('hide');
            }
        }
    );

    /*$('.datetimepicker').datetimepicker({
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        },
        changeDate: function(e){
            console.log(this);
        }
    });*/

    $('.date').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        language: "ru"
    });
});

$(function () {
    //Ajax links
    $(document).on('click', 'a[data-target]', function (event) {
        target = $(this).attr('data-target');
        url = $(this).attr('href');
        if ($(this).parents('.elements-menu').length) {
            $('table.selectable input:checked').each(function () {
                url = url + '&' + $(this).attr('name') + '=' + $(this).val();
            });
        }
        console.log($(this).parents('.input-group'));
        if ($(this).parents('.input-group').length) {
            input = $(this).parents('.input-group').find('input, select');
            console.log(input);
            url += '&elements[]=' + input.val();
        }
        $(target + ' > *').remove();
        $(target).addClass('loading');
        $(target).parents('.modal').modal('show');
        $.ajax({
            url: url,
            cache: false,
            success: function (data) {
                $(target).removeClass('loading');
                $(target).html(data);
            }
        });
        event.preventDefault();
    });

    //Fileupload
    //Form
    var form = $('#fileupload-form');
    if (!form.size()) {
        form = $('<form id="fileupload-form" target="fileupload-iframe" method="post" enctype="multipart/form-data" encoding="multipart/form-data" style="display:none;"><input type="file" name="file"></form>');
        $('body').append(form);
    }

    //Fileinput
    var fileinput = form.find('input[name="file"]');
    fileinput.change(function (event) {
        form.submit();
    });

    //Iframe
    var iframe = $('#fileupload-iframe');
    if (!iframe.size()) {
        iframe = $('<iframe id="fileupload-iframe" name="fileupload-iframe" style="display:none;" />');
        $('body').append(iframe);
    }
    iframe.off('load');
    iframe.off('onload');

    $('.modal-body').on('click', '.fileupload-button', function (event) {
        var button = $(this);
        form.attr('action', $(this).attr('data-fileupload-action'));
        var target = button.parents('.form-group').find('input[type="text"]');
        var file_name = '';
        iframe.unbind('load');
        iframe.bind('load', function (event) {
            file_name = $(this).contents().find("body").html();
            target.val(file_name);
            button.parent().find('img').remove();
            button.parent().append('<div class="row"><div class="col-sm-6 col-md-3"><a href="#" class="thumbnail"><img src="/upload/files/' + file_name + '" class="img-responsive"></a></div></div>');
        });
        fileinput.click();
        event.preventDefault();
    });

    //Add element form panel
    $('.modal-body').on('change', 'form.form-horizontal .elements.selectpicker', function () {
        //console.log($(this));
        var v = $(this).val();
        if (v == 'add') {
            var name = $(this).attr('name');
            console.log(name);
            var obj=$(this)[0];
            var id = obj.dataset.field + '_form';
            var formUrl = obj.dataset.form;
            var title = $(this).parents('.form-group').find('label').text();
            $(this).parents('.form-group').after('<div class="panel panel-default" id="' + id + '"><div class="panel-heading"><button type="button" class="close pull-right" data-dismiss="panel"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h3 class="panel-title">' + title + '</h3></div><div class="panel-body">Panel content</div></div>');
            $.ajax({
                url: formUrl + obj.dataset.type + '?buttons=no&tabs=no&into=' + name,
                cache: false,
                success: function (data) {
                    //$('.panel-body').removeClass('loading');
                    $('#' + id + ' .panel-body').html(data);
                }
            });
        }
    });

    //Preload images
    $.fn.preload = function () {
        this.each(function () {
            $('<img/>')[0].src = this;
        });
    }
    $(['/admin/static/images/ajax-loader.gif', '/admin/static/images/ajax-loader_small.gif']).preload();

    function openKCFinder(field) {
        window.KCFinder = {
            callBack: function (url) {
                var filename = url.substring(url.lastIndexOf('/') + 1);
                $(field).val(filename);
                window.KCFinder = null;
            }
        };
        window.open('/admin/plugins/kcfinder/browse.php?type=Files', 'kcfinder_textbox',
            'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
                'resizable=1, scrollbars=0, width=800, height=600'
        );
    }
});

