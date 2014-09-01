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
    <link href="/admin/static/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="/admin/static/css/jquery-ui.css">

    <!-- JavaScript plugins (requires jQuery) -->
    <script type="text/javascript" src="/admin/static/js/jquery-1.9.0.min.js"></script>
    <script src="/admin/static/js/jquery-migrate-1.2.1.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/admin/static/js/moment-2.4.0.js"></script>
    <script src="/admin/static/js/bootstrap.min.js"></script>

    <!--jQuery UI-->
    <script src="/admin/static/js/jquery-ui-1.10.3.custom.js"></script>

    <!--Hashchange event-->
    <script type="text/javascript" src="/admin/static/js/jquery.ba-hashchange.js"></script>

    <!--Bootstrap-select v1.3.5-->
    <script type="text/javascript" src="/admin/static/js/bootstrap-select.js"></script>
    <link rel="stylesheet" type="text/css" href="/admin/static/css/bootstrap-select.css">

    <script src="/admin/plugins/ckeditor/ckeditor.js"></script>
    <script src="/admin/plugins/ckeditor/adapters/jquery.js"></script>

    <!--Bootstrap datetimepicker -->
    <script type="text/javascript" src="/admin/plugins/datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/admin/plugins/datetimepicker/build/css/bootstrap-datetimepicker.min.css">

    <!--Bootstrap datepicker -->
    <link id="bsdp-css" href="/admin/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet">
    <script src="/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="/admin/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.ar.js" charset="UTF-8"></script>

    <!--Custom Elementus Admin styles-->
    <link href="/admin/static/css/custom.css" rel="stylesheet" media="screen">

    <script type="text/javascript" src="/admin/static/js/elementus.js"></script>

    <script type="text/javascript">

        $(function(){
            $(window).hashchange( function(){
                var hash = location.hash;
                var url = hash.replace( /^#\//, '' );
                $("a.selected").removeClass('selected');
                $("a[href~='"+hash+"']").addClass('selected');
                $("#page").addClass('loading');
                $("#page > *").remove();
                $.ajax({
                    url: url,
                    success:function(html){
                        $("#page").removeClass('loading');
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

        function setcookie(name, value, expires, path, domain, secure) {	// Send a cookie
            //
            // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)

            expires instanceof Date ? expires = expires.toGMTString() : typeof(expires) == 'number' && (expires = (new Date(+(new Date) + expires * 1e3)).toGMTString());
            var r = [name + "=" + escape(value)], s, i;
            for(i in s = {expires: expires, path: path, domain: domain}){
                s[i] && r.push(i + "=" + s[i]);
            }
            return secure && r.push("secure"), document.cookie = r.join(";"), true;
        }
    </script>
</head>
<body>
<!-- Static navbar -->
<div class="navbar navbar-default navbar-static-top">
    <a class="navbar-brand logo" href="#/dashboard"><img src="static/images/logo.png"></a>
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="fa fa-bar"></span>
            <span class="fa fa-bar"></span>
            <span class="fa fa-bar"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            <li class="active"><a href="#/dashboard"><i class="fa fa-th"></i> Dashboard</a></li>
            <li><a href="#/app_form/act/edit/id/<?=E::$app['id']?>"><i class="fa fa-cog"></i> <?=t('Settings')?></a></li>
            <li><a href="#/types"><i class="fa fa-code-fork"></i> <?=t('Types')?></a></li>
            <li><a href="/admin/plugins/kcfinder/browse.php?type=Files"><i class="fa fa-columns"></i> Files</a></li>
            <li><a href="#/console"><i class="fa fa-code-fork"></i> <?=t('Console')?></a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=E::$lang?> <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#" onClick="setcookie('lang','en'); window.location.href='/'; return false;">EN</a></li>
                    <li><a href="#" onClick="setcookie('lang','ru'); window.location.href='/'; return false;">RU</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick="setcookie('langMenu','1'); window.location.href='/'; return false;"><?=t('Customize')?></a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?=Users::$user['email']?> <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="/admin/index.php?exit=1"><?=t('Exit')?></a></li>
                    <li><a href="#"><?=t('Settings')?></a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
