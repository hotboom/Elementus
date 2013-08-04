<? require_once("../core/init.php"); ?>
<? require_once("pages/header.php"); ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <h2><a href="/">Sections</a></h2>
                <?
                function tree($parent_id=0,$class='sections'){
                    $sections=Sections::getList($parent_id);
                    if(empty($sections)) return false;

                    if($parent_id==0) echo '<ul class="'.$class.'">';
                    else echo '<ul>';
                    foreach($sections as $section){
                        echo '<li><a href="#/section/'.$section['id'].'">'.$section['name'].'</a>';
                        tree($section['id']);
                        echo '</li>';
                    }
                    echo '</ul>';
                    return true;
                }
                tree(0);
                ?>
            </div>
            <div id="page" class="col-lg-10">

            </div>
        </div>
    </div><!-- /.container -->
<? require_once("pages/footer.php"); ?>