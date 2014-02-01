<? include("header.php"); ?>
<? include("category.php"); ?>
<div class="leftbar" style="width:300px;">
    <?
    //Функция для рекурсивного вывода меню раздела
    function tree2($parent_id=0){
        $sections=S::getList($parent_id);
        if(empty($sections)) return false;
        foreach($sections as $section){
            if(!$section['public']) continue;
            if(empty($section['link'])) $section['link']='/'.$section['path'];
            echo '<li><a href="'.$section['link'].'" '.(strpos($section['link'],'http://')!==false ? 'target="_blank"' : '').'>'.$section['name'].'</a>';
            echo '<ul>';
            tree2($section['id']);
            echo '</ul>';
            echo '</li>';
        }
        return true;
    }
    $parents=S::getParents(S::$section['id']);
    if(!empty($parents)):
        echo '<h2>Разделы</h2>';
        echo '<ul class="leftmenu">';
        tree2($parents[count($parents)-1]); //Отображение древовидного меню первого предка текущего раздела
        echo '</ul>';
    elseif(S::getList($_E['section']['id'])!=array()):
        echo '<h2>Разделы</h2>';
        echo '<ul class="leftmenu">';
        tree2(S::$section['id']);
        echo '</ul>';
    endif; ?>
</div>
<div class="rightbar" style="width:750px;">
    <h1><?=S::$section['name']?></h1>
    <? if($content=E::get(array(
        'type'=>'content',
        'filter'=>'section_id='.S::$section['id']
    ))): ?>
        <? foreach($content as $text):?>
            <?=$text['content']?>
        <? endforeach;?>
    <? endif;?>
</div>
<br class="clear">
<? include("footer.php"); ?>
