<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Elemental</title>
    <!-- Bootstrap -->
    <link href="/admin/static/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="/admin/static/css/bootstrap-glyphicons.css" rel="stylesheet" media="screen">
    <link href="/admin/static/css/custom.css" rel="stylesheet" media="screen">

    <!-- JavaScript plugins (requires jQuery) -->
    <script type="text/javascript" src="/admin//static/js/jquery-1.9.0.min.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/admin/static/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/admin/static/js/jquery.address-1.6.min.js"></script>
    <script type="text/javascript">
        // Event handlers
        $.address.init(function(event) {

        }).change(function(event) {
            if(event.value=='/') var url='page/dashboard';
            else var url='/admin'+event.value;
            $.deepLink(url);
        });
        $.deepLink=function(url){
            $.ajax({
                url: url
            }).done(function( html ) {
                    $("#page").html(html);
            });
        }


    </script>

</head>
<body>
<div class="navbar">
    <div class="container">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Elemental</a>
        <div class="nav-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#/page/dashboard">Dashboard</a></li>
                <li><a href="#/users">Users</a></li>
                <li><a href="#/files">Files</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>