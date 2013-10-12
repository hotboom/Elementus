<?
$field=$data['field'];
$element=$data['element'];
?>

<div id="toolbar<?=$field['name']?>" style="display: none;">
    <? include($root_path."/admin/static/html/toolbar.tpl.html");?>
</div>

<textarea name="<?=$data['name']?>" id="input<?=$field['name']?>" class="form-control" rows="6" placeholder="Enter text ..." style="width:100%;"><?=$element[$field['name']]?></textarea>
<script>
    var editor = new wysihtml5.Editor("input<?=$field['name']?>", {
        toolbar:      "toolbar<?=$field['name']?>",
        //stylesheets:  "css/stylesheet.css",
        parserRules:  wysihtml5ParserRules
    });
</script>