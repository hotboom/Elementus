<?if($data['field']['type']==='elements'):?>
    <? Template::render('pages/field_types/elements.php',$data); ?>
<?elseif($data['field']['type']==='enum'): ?>
    <? Template::render('pages/field_types/enum.php',$data); ?>
<?else:?>
    <? Template::render('pages/field_types/default.php',$data); ?>
<?endif;?>