<?
$data['field']['default']='';
$data['filter']=true;

?>
<?if($data['field']['type']==='elements'):?>
    <? Template::render('pages/field_types/elements.php',$data); ?>
<?elseif($data['field']['type']==='enum'): ?>
    <? Template::render('pages/field_types/enum.php',$data); ?>
<?else:?>
    <? if($data['field']['name']=='id'):?>
        <input type="checkbox" id="selectAll" class="pull-left">
    <? endif;?>
    <? Template::render('pages/field_types/default.php',$data); ?>
<?endif;?>