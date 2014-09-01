<?
/* Shortcut functions */
//Translate
function t($text,$ucfirst='auto',$lang='en'){
    return E::translate($text,$ucfirst,$lang);
}

if(!function_exists('mb_ucfirst')) {
    function mb_ucfirst($str, $enc = 'utf-8') {
        return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc);
    }
}

function my_json_encode($arr)
{
    //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
    array_walk_recursive($arr, function (&$item, $key) { if (is_string($item)) $item = mb_encode_numericentity($item, array (0x80, 0xffff, 0, 0xffff), 'UTF-8'); });
    return mb_decode_numericentity(json_encode($arr), array (0x80, 0xffff, 0, 0xffff), 'UTF-8');

}

function date_rus($date=false){
    if(!$date) $date=date("d.m.Y");
    else{
        if(is_numeric($date)) $date=date("d.m.Y",$date);
        else $date=date("d.m.Y",strtotime($date));
    }
    $date=explode(".", $date);
    switch ($date[1]){
        case 1: $m='января'; break;
        case 2: $m='февраля'; break;
        case 3: $m='марта'; break;
        case 4: $m='апреля'; break;
        case 5: $m='мая'; break;
        case 6: $m='июня'; break;
        case 7: $m='июля'; break;
        case 8: $m='августа'; break;
        case 9: $m='сентября'; break;
        case 10: $m='октября'; break;
        case 11: $m='ноября'; break;
        case 12: $m='декабря'; break;
    }
    return '«'.$date[0].'» '.$m.' '.$date[2].'г.';
}

function translit($text, $mode='file'){
    $translitRus['а']="a";
    $translitRus['б']="b";
    $translitRus['в']="v";
    $translitRus['г']="g";
    $translitRus['д']="d";
    $translitRus['е']="e";
    $translitRus['ё']="e";
    $translitRus['ж']="zh";
    $translitRus['з']="z";
    $translitRus['и']="i";
    $translitRus['й']="i";
    $translitRus['к']="k";
    $translitRus['л']="l";
    $translitRus['м']="m";
    $translitRus['н']="n";
    $translitRus['о']="o";
    $translitRus['п']="p";
    $translitRus['р']="r";
    $translitRus['с']="s";
    $translitRus['т']="t";
    $translitRus['у']="u";
    $translitRus['ф']="f";
    $translitRus['х']="h";
    $translitRus['ц']="ts";
    $translitRus['ч']="ch";
    $translitRus['ш']="sch";
    $translitRus['щ']="sch";
    $translitRus['э']="e";
    $translitRus['ы']="s";
    $translitRus['ю']="yu";
    $translitRus['я']="ya";
    $translitRus['ь']="'";
    $translitRus['ъ']="'";

    $translitRus['A']="A";
    $translitRus['Б']="B";
    $translitRus['B']="V";
    $translitRus['Г']="G";
    $translitRus['Д']="D";
    $translitRus['E']="E";
    $translitRus['Ё']="E";
    $translitRus['Ж']="ZH";
    $translitRus['З']="Z";
    $translitRus['И']="I";
    $translitRus['Й']="I";
    $translitRus['К']="K";
    $translitRus['Л']="L";
    $translitRus['М']="M";
    $translitRus['Н']="N";
    $translitRus['О']="O";
    $translitRus['П']="P";
    $translitRus['Р']="R";
    $translitRus['С']="S";
    $translitRus['Т']="T";
    $translitRus['У']="U";
    $translitRus['Ф']="F";
    $translitRus['Х']="H";
    $translitRus['Ц']="TS";
    $translitRus['Ч']="CH";
    $translitRus['Ш']="SCH";
    $translitRus['Щ']="SCH";
    $translitRus['Э']="E";
    $translitRus['Ы']="S";
    $translitRus['Ю']="YU";
    $translitRus['Я']="YA";
    $translitRus['Ь']="'";
    $translitRus['Ъ']="'";

    $specChars[]='"';
    $specChars[]="'";
    $specChars[]=",";
    $specChars[]="!";
    $specChars[]="?";
    $specChars[]="~";
    $specChars[]="`";
    $specChars[]="@";
    $specChars[]="№";
    $specChars[]="#";
    $specChars[]="$";
    $specChars[]="%";
    $specChars[]="^";
    $specChars[]=":";
    $specChars[]=";";

    //Processing text
    foreach($translitRus as $i=>$val){
        $text=str_replace($i, $val, $text);
    }
    if($mode=="space") $text=str_replace(" ", "_", $text);
    if($mode=="file"){
        foreach($specChars as $val){
            $text=str_replace($val, "", $text);
        }
        $text=str_replace(" ", "_", $text);
    }
    return $text;
}