<?
$field=$data['field'];
$element=$data['element'];
?>
<? if($field['name']!='password'): ?>
<div class="form-group">
    <label class="col-lg-2 control-label" for="input<?=$field['name']?>"><?=t($field['name'],true)?></label>
    <div class="col-lg-10">
        <?if($field['type']==='elements'):?>
            <? Template::render('pages/field_types/elements.php',$data); ?>
        <?elseif($field['type']==='enum'): ?>
            <? Template::render('pages/field_types/enum.php',$data); ?>
        <?elseif($field['type']==='text'): ?>
            <? Template::render('pages/field_types/text.php',$data); ?>
        <?elseif($field['type']==='html'): ?>
            <? Template::render('pages/field_types/html.php',$data); ?>
        <?elseif($field['type']==='file'|$field['type']==='image'): ?>
            <? Template::render('pages/field_types/file.php',$data); ?>
        <?elseif($field['type']==='datetime'): ?>
            <? Template::render('pages/field_types/datetime.php',$data); ?>
        <?else: ?>
            <? Template::render('pages/field_types/default.php',$data); ?>
        <?endif; ?>
    </div>
</div>
<? else: ?>
<div class="form-group">
    <label class="col-lg-2 control-label" for="input<?=$field['name']?>"><?=t($field['name'])?> <?=t('hash')?></label>
    <div class="col-lg-10">
        <input name="<?=$data['name']?>" type="text" class="form-control" id="input<?=$field['name']?>" value="<?=$data['value']?>">
    </div>
</div>
<div class="form-group">
    <label class="col-lg-2 control-label" for="inputnew<?=$field['name']?>"><?=t('New')?> <?=t($field['name'])?></label>
    <div class="col-lg-10">
        <div class="input-group">
            <input type="text" name="fields[new_<?=$field['name']?>]" type="text" class="form-control" id="inputnew<?=$field['name']?>">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button" onClick="$(this).parents('.input-group').find('input').val(getPassword(8, true, true, true, true, false, true, true, true, false)); return false;"><?=t('generate')?></button>
            </span>
        </div><!-- /input-group -->
    </div>
</div>
<script>
    function getRandomNum(lbound, ubound) {
        return (Math.floor(Math.random() * (ubound - lbound)) + lbound);
    }
    function getRandomChar(number, lower, upper, other, extra) {
        var numberChars = "0123456789";
        var lowerChars = "abcdefghijklmnopqrstuvwxyz";
        var upperChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        var otherChars = "`~!@#$%^&*()-_=+[{]}\\|;:'\",<.>/? ";
        var charSet = extra;
        if (number == true)
            charSet += numberChars;
        if (lower == true)
            charSet += lowerChars;
        if (upper == true)
            charSet += upperChars;
        if (other == true)
            charSet += otherChars;
        return charSet.charAt(getRandomNum(0, charSet.length));
    }
    function getPassword(length, extraChars, firstNumber, firstLower, firstUpper, firstOther, latterNumber, latterLower, latterUpper, latterOther) {
        var rc = "";
        if (length > 0)
            rc = rc + getRandomChar(firstNumber, firstLower, firstUpper, firstOther, extraChars);
        for (var idx = 1; idx < length; ++idx) {
            rc = rc + getRandomChar(latterNumber, latterLower, latterUpper, latterOther, extraChars);
        }
        return rc;
    }
</script>
<? endif;?>