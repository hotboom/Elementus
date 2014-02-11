<?
$widgets=E::get(array(
    'type'=>'widgets'
));

foreach($widgets as $widget):
    $widget['type']=E::getType($widget['type_id']);
    $widget['count']=E::get(array('type'=>$widget['type_id'],'count'=>true));
    $c='count(*)';
    ?>
    <div class="panel panel-default">
        <div class="panel-heading"><?=t($widget['name'])?></div>
        <div class="panel-body">
            <div class="btn-group">
                <a href="#/type/id/<?=$widget['type']['id']?>" type="button" class="btn btn-default">Обзор <span class="badge"><?=$widget['count'][0][$c]?></span></a>
                <a type="button" class="btn btn-default">Добавить</a>
                <a type="button" class="btn btn-default">Категории</a>
            </div>
            <div class="elements"></div>
            <script>
                $.ajax({
                    url: '/admin/index.php?page=type&id=<?=$widget['type']['id']?>&mode=compact',
                    success:function(data){
                        $('.elements').html(data);
                    }
                });
            </script>
        </div>
    </div>
<? endforeach;?>