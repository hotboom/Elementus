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