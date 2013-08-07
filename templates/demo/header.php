<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Elementus framework</title>

    <!-- Bootstrap core CSS -->
    <link href="/static/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/static/css/jumbotron.css" rel="stylesheet">
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
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

            <form class="navbar-form form-inline pull-right">
                <input type="text" placeholder="Email">
                <input type="password" placeholder="Password">
                <button type="submit" class="btn btn-primary">Войти</button>
            </form>
        </div><!--/.nav-collapse -->
    </div>
</div>

<div class="container">