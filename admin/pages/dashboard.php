<?
$widgets=E::get(array(
    'type'=>'widgets'
));

foreach($widgets as $widget):
    $widget['type']=E::getType($widget['type_id']);
    $widget['count']=E::get(array('type'=>$widget['type_id'],'count'=>true));
    ?>
    <div class="panel panel-default">
        <div class="panel-heading"><?=mb_ucfirst($widget['name'])?></div>
        <div class="panel-body">
            <div class="btn-group">
                <a href="#/type/id/<?=$widget['type']['id']?>" type="button" class="btn btn-default">Обзор <span class="badge"><?=$widget['count']?></span></a>
                <a type="button" class="btn btn-default">Добавить</a>
                <a type="button" class="btn btn-default">Категории</a>
            </div>
            <div class="elements"></div>
            <script>
                $.ajax({
                    url: '/admin/index.php?page=type&id=<?=$widget['type']['id']?>&order=<?=$widget['order']?><?=($widget['sort']=='DESC' ? '&desc=1' : '')?><?=($widget['limit'] ? '&limit='.$widget['limit'] : '')?>&mode=compact',
                    success:function(data){
                        $('.elements').html(data);
                    }
                });
            </script>
        </div>
    </div>
<? endforeach;?>