<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Elementus</title>
    <!-- Bootstrap -->
    <link href="/admin/static/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">

    <!--Custom Elementus Admin styles-->
    <link href="/admin/static/css/wysihtml5-custom.css" rel="stylesheet" media="screen">
    <link href="/admin/static/css/custom.css" rel="stylesheet" media="screen">

    <!-- JavaScript plugins (requires jQuery) -->
    <script type="text/javascript" src="/admin/static/js/jquery-1.9.0.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

    <!-- wysihtml5 editor -->
    <script src="/admin/static/js/advanced.js"></script>
    <script src="/admin/static/js/wysihtml5-0.3.0.js"></script>

    <!--jQuery UI-->
    <script src="/admin/static/js/jquery-ui-1.10.3.custom.js"></script>

    <!--Hashchange event-->
    <script type="text/javascript" src="/admin/static/js/jquery.ba-hashchange.js"></script>

    <script type="text/javascript">

        $(function(){
            $(window).hashchange( function(){
                var hash = location.hash;
                var url = hash.replace( /^#\//, '' );
                $("a.selected").removeClass('selected');
                $("a[href~='"+hash+"'").addClass('selected');

                $.ajax({
                    url: url,
                    success:function(html){
                        $("#page > *").remove();
                        $("#page").html(html);
                    }
                });
            });
            if(!location.hash) location.hash='#/dashboard';
            $(window).hashchange();
        });

        $(function() {
            //Make Bootstrap modal resizable and draggable
            $( ".modal-content" ).resizable();
            $( ".modal-inner" ).draggable({ handle: ".modal-header" });
            $('#window').on('shown.bs.modal', function () {
                var content=$('.modal-content');
                content.resizable( "option", "minWidth", content.width() );
                content.resizable( "option", "minHeight", content.height() );
            });
        });
    </script>
</head>
<body>
<!-- Static navbar -->
<div class="navbar navbar-default navbar-static-top">
    <a class="navbar-brand" href="#/dashboard">Elementus</a>
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            <li class="active"><a href="#/dashboard"><i class="icon-th"></i> Dashboard</a></li>
            <li><a href="#/app_form/act/edit/id/<?=E::$app['id']?>"><i class="icon-cog"></i> <?=t('Settings')?></a></li>
            <li><a href="#/types"><i class="icon-code-fork"></i> <?=t('Types')?></a></li>
            <li><a href="#/files"><i class="icon-columns"></i> Files</a></li>
        </ul>
        <form class="navbar-form form-inline pull-right">
            <label><?=Users::$user['email']?></label> <a href="/admin/index.php?exit=1" class="btn btn-primary"><i class="icon-code-right"></i>  <?=t('Exit')?></a>
        </form>
    </div><!--/.nav-collapse -->
</div>