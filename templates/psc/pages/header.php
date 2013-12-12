<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Сатурн-Р</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,300|Open+Sans+Condensed:700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href="/templates/psc/static/css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="wrapper">
    <div class="header">
        <a href="/" class="logo"></a>
        <a class="city" href="#"><span>Пермь</span></a>
        <ul class="mmenu">
        <?
        $sections=S::getList(0);
        foreach($sections as $section):
            if($section['show']&&$section['public']):?>
            <li><a href="/<?=$section['path']?>"><?=$section['name']?></a></li>
            <? endif;?>
        <? endforeach;?>
        </ul>
        ?>
    </div>
    <div class="page">