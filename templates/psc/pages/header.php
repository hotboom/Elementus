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
        <?
        function tree($parent_id=0){
            $sections=Sections::getList($parent_id);
            if(empty($sections)) return false;

            if($parent_id==0) echo '<ul class="mmenu">';
            else echo '<ul>';
            foreach($sections as $section){
                if(empty($section['link'])) $section['link']='/'.$section['path'].'/';
                echo '<li><a href="'.$section['link'].'" '.(strpos($section['link'],'http://')!==false ? 'target="_blank"' : '').'>'.$section['name'].'</a>';
                tree($section['id']);
                echo '</li>';
            }
            echo '</ul>';
            return true;
        }
        tree();
        ?>
    </div>
    <div class="page">