<?
$widgets=E::get(array(
    'type'=>'widgets'
));

foreach($widgets as $widget):?>
    <div class="panel panel-default">
        <div class="panel-heading"><?=t($widget['name'])?></div>
        <div class="panel-body">
            <div class="btn-group">
                <button type="button" class="btn btn-default">Обзор <span class="badge">42</span></button>
                <button type="button" class="btn btn-default">Добавить</button>
                <button type="button" class="btn btn-default">Категории</button>
            </div>
        </div>
    </div>
<? endforeach;?>