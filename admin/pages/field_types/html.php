<?
$field=$data['field'];
$element=$data['element'];
?>

<div id="toolbar<?=$field['name']?>" style="display: none;">
    <? include($root_path."/admin/static/html/toolbar.tpl.html");?>
</div>

<textarea name="<?=$data['name']?>" id="<?=(empty($data['id']) ? 'input_'.$field['name'] : $data['id'])?>" class="form-control" rows="6" placeholder="Enter text ..." style="width:100%;" data-field="<?=$field['name']?>"><?=$data['value']?></textarea>
<script>
    var editor = new wysihtml5.Editor("<?=(empty($data['id']) ? 'input_'.$field['name'] : $data['id'])?>", {
        toolbar:      "toolbar<?=$field['name']?>",
        //stylesheets:  "css/stylesheet.css",
        parserRules:  wysihtml5ParserRules
    });
</script>