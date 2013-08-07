<div class="row">
    <div class="col-lg-2">
        <h3>Навигация</h3>
        <ul>
            <li>
            </li>
        </ul>
    </div>
    <div class="col-lg-10">
        <h1><?=$data['section']['name']?></h1>
        <? if($content=Elements::get(array('filter'=>array('type'=>'content','section_id'=>$data['section']['id'])))): ?>
        <? foreach($content as $text):?>
            <h2><?=$text['header']?></h2>
            <?=$text['content']?>
        <? endforeach;?>
        <? endif;?>
    </div>

</div>
</div>