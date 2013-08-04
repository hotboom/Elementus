<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <?
            /* function tree($parent_id=0,$class='sections'){
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
            */
            ?>
        </div>
        <div class="col-lg-8">
            <h1>Dashboard</h1>
            <?
            /* function treeTypes($parent_id=0,$class='types'){
                $types=Elements::getTypes(array('parent'=>$parent_id));
                if(empty($types)) return false;

                if($parent_id==0) echo '<ul class="'.$class.'">';
                else echo '<ul>';
                foreach($types as $type){
                    echo '<li><a href="#/type/'.$type['id'].'">'.$type['name'].'</a>';
                    treeTypes($type['id']);
                    echo '</li>';
                }
                echo '</ul>';
                return true;
            }
            treeTypes(0); */

            //ok let's get some fun
            Elements::debug();

            $elements=Elements::get('bikes',array(
                'limit'=>30,
                'filter'=>array(
                    'frame_size'=>'18',
                    array('store_moskow >'=>1,'|','store_perm >'=>1),
                    'brand !='=>'GT'
                )
            ));
            print_r($elements);
            ?>
        </div>
    </div>
</div><!-- /.container -->