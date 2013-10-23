<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Elementus framework</title>

    <!-- Bootstrap core CSS -->
    <link href="/templates/elementus/static/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/templates/elementus/static/css/jumbotron.css" rel="stylesheet">

    <!-- JavaScript plugins (requires jQuery) -->
    <script src="/templates/elementus/static/js/jquery-1.10.2.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/templates/elementus/static/js/bootstrap.min.js"></script>

    <!-- Enable responsive features in IE8 with Respond.js (https://github.com/scottjehl/Respond) -->
    <!-- <script src="js/respond.js"></script> -->
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
            <span class="fa fa-bar"></span>
            <span class="fa fa-bar"></span>
            <span class="fa fa-bar"></span>
        </button>
        <a class="navbar-brand" href="/">Elemental</a>
        <div class="nav-collapse collapse">
            <?
            function tree($parent_id=0){
                $sections=Sections::getList($parent_id);
                if(empty($sections)) return false;

                if($parent_id==0) echo '<ul class="nav navbar-nav">';
                else echo '<ul>';
                foreach($sections as $section){
                    if(empty($section['link'])) $section['link']='/'.$section['path'].'/';
                    echo '<li><a href="'.$section['link'].'">'.$section['name'].'</a>';
                    tree($section['id']);
                    echo '</li>';
                }
                echo '</ul>';
                return true;
            }
            tree(0);
            ?>
            <? if(Users::$user):?>
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown">
                    <a data-toggle="dropdown" href="#"><?=Users::$user['email']?> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        <li><a href="#">Profile</a></li>
                        <li><a href="/main/act/logout"><?=t('Exit')?></a></li>
                        <? if(Users::$user['group_id']=='19'):?><li><a href="/admin"><?=t('Admin panel')?></a></li><? endif;?>
                    </ul>
                </li>
            </ul>
            <? else:?>
            <form class="navbar-form form-inline pull-right" method="POST" data-async data-target="#window .modal-body" action="index.php?act=login">
                <input type="text" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <button type="submit" class="btn btn-primary btn-small">Войти</button>
            </form>
            <? endif;?>
        </div><!--/.nav-collapse -->
    </div>
</div>

<div class="container">