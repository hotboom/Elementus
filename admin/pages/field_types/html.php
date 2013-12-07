<?
$field=$data['field'];
$element=$data['element'];
$field['id']=(empty($data['id']) ? 'input_'.$field['name'] : $data['id'])
?>
<textarea name="<?=$data['name']?>" id="<?=$field['id']?>" name="<?=$field['id']?>" class="form-control" rows="24" placeholder="Enter text ..." style="width:100%;" data-field="<?=$field['name']?>"><?=$data['value']?></textarea>
<?
if(!$CKload):?>
    <script src="/admin/plugins/ckeditor/ckeditor.js"></script>
    <script src="/admin/plugins/ckeditor/adapters/jquery.js"></script>
    <!--<script type="text/javascript">
        CKEDITOR.editorConfig = function(config) {
            config.filebrowserBrowseUrl = '/admin/plugins/kcfinder/browse.php?type=files';
            config.filebrowserImageBrowseUrl = '/admin/plugins/kcfinder/browse.php?type=images';
            config.filebrowserFlashBrowseUrl = '/admin/plugins/kcfinder/browse.php?type=flash';
            config.filebrowserUploadUrl = '/admin/plugins/kcfinder/upload.php?type=files';
            config.filebrowserImageUploadUrl = '/admin/plugins/kcfinder/upload.php?type=images';
            config.filebrowserFlashUploadUrl = '/admin/plugins/kcfinder/upload.php?type=flash';
        };
    </script>-->
<? endif;
$CKload=true; ?>
<script>
    $( document ).ready( function() {
        $( '#<?=$field['id']?>' ).ckeditor({
            customConfig: '/admin/static/js/ckeditor_config.js'
        });
    });
</script>