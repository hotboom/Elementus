<div class="form-group">
    <label class="col-lg-2 control-label" for="input<?=$data['field']['name']?>"><?=t($data['field']['name'])?> <?=t('hash')?></label>
    <div class="col-lg-10">
        <input name="<?=$data['name']?>" type="text" class="form-control" id="input<?=$data['field']['name']?>" value="<?=$data['value']?>">
    </div>
</div>
<div class="form-group">
    <label class="col-lg-2 control-label" for="inputnew<?=$data['field']['name']?>"><?=t('New')?> <?=t($data['field']['name'])?></label>
    <div class="col-lg-10">
        <div class="input-group">
            <input type="text" name="fields[new_<?=$data['field']['name']?>]" type="text" class="form-control" id="inputnew<?=$data['field']['name']?>">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button" onClick="$(this).parents('.input-group').find('input').val(getPassword(8, true, true, true, true, false, true, true, true, false)); return false;"><?=t('generate')?></button>
            </span>
        </div><!-- /input-group -->
    </div>
    <? if(!empty($data['field']['help'])):?>
        <span class="help-block"><?=$data['field']['help']?></span>
    <? endif;?>
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