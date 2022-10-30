<?php
session_start();

if (!isset($_SESSION["logged_in"]) || isset($_GET['logout'])) {
    session_destroy();
    header("location: /a/index.php");
    die();
}

$categories = json_decode(file_get_contents("../json/categories.json"), true);
$products = json_decode(file_get_contents("../json/products.json"), true);
$brands = json_decode(file_get_contents("../json/brands.json"), true);
$fr = json_decode(file_get_contents("../translate/data-fr.json"), true);
$en = json_decode(file_get_contents("../translate/data-en.json"), true);
$it = json_decode(file_get_contents("../translate/data-it.json"), true);
$ar = json_decode(file_get_contents("../translate/data-ar.json"), true);

$id = (int)($products[count($products) - 1]['id']) + 1;
$HEADER = '<span id="header"></span>';
$FOOTER = '<span id="footer"></span>';
$SLIDE = '<span id="slider"></span>';
$SIDE = '<span id="aside"></span>';

function clean($str)
{
    $str = strip_tags($str);

    $str = preg_replace('/’/', '', $str);
    $str = preg_replace('/‘/', '', $str);

    $str = preg_replace('/"/', '', $str);
    $str = preg_replace("/'/", '', $str);

    $str = preg_replace("/\//", '', $str);

    $str = preg_replace('/[-]/', ' ', $str);
    $str = preg_replace("/:/", '-', $str);

    $str = preg_replace('/  */', '-', $str);
    $str = preg_replace('/\\s+/', '-', $str);
    return $str;
}

function _clean($str)
{
    if ($str[strlen($str) - 1] == "-")  $str = substr($str, 0, -1);
    if ($str[0] == "-")  $str = substr($str, 0, 1);
    $latin = [
        '½' => '-',
        '«' => '-',
        '»' => '-',
        '°' => '',
        '.' => '-',
        '+' => '-',
        '(' => '-',
        ')' => '-',
        ';' => '-',
        ',' => '-',
        'Á' => 'A',
        'Ă' => 'A',
        'Ắ' => 'A',
        'Ặ' => 'A',
        'Ằ' => 'A',
        'Ẳ' => 'A',
        'Ẵ' => 'A',
        'Ǎ' => 'A',
        'Â' => 'A',
        'Ấ' => 'A',
        'Ậ' => 'A',
        'Ầ' => 'A',
        'Ẩ' => 'A',
        'Ẫ' => 'A',
        'Ä' => 'A',
        'Ǟ' => 'A',
        'Ȧ' => 'A',
        'Ǡ' => 'A',
        'Ạ' => 'A',
        'Ȁ' => 'A',
        'À' => 'A',
        'Ả' => 'A',
        'Ȃ' => 'A',
        'Ā' => 'A',
        'Ą' => 'A',
        'Å' => 'A',
        'Ǻ' => 'A',
        'Ḁ' => 'A',
        'Ⱥ' => 'A',
        'Ã' => 'A',
        'Ꜳ' => 'AA',
        'Æ' => 'AE',
        'Ǽ' => 'AE',
        'Ǣ' => 'AE',
        'Ꜵ' => 'AO',
        'Ꜷ' => 'AU',
        'Ꜹ' => 'AV',
        'Ꜻ' => 'AV',
        'Ꜽ' => 'AY',
        'Ḃ' => 'B',
        'Ḅ' => 'B',
        'Ɓ' => 'B',
        'Ḇ' => 'B',
        'Ƀ' => 'B',
        'Ƃ' => 'B',
        'Ć' => 'C',
        'Č' => 'C',
        'Ç' => 'C',
        'Ḉ' => 'C',
        'Ĉ' => 'C',
        'Ċ' => 'C',
        'Ƈ' => 'C',
        'Ȼ' => 'C',
        'Ď' => 'D',
        'Ḑ' => 'D',
        'Ḓ' => 'D',
        'Ḋ' => 'D',
        'Ḍ' => 'D',
        'Ɗ' => 'D',
        'Ḏ' => 'D',
        'ǲ' => 'D',
        'ǅ' => 'D',
        'Đ' => 'D',
        'Ð' => 'D',
        'Ƌ' => 'D',
        'Ǳ' => 'DZ',
        'Ǆ' => 'DZ',
        'É' => 'E',
        'Ĕ' => 'E',
        'Ě' => 'E',
        'Ȩ' => 'E',
        'Ḝ' => 'E',
        'Ê' => 'E',
        'Ế' => 'E',
        'Ệ' => 'E',
        'Ề' => 'E',
        'Ể' => 'E',
        'Ễ' => 'E',
        'Ḙ' => 'E',
        'Ë' => 'E',
        'Ė' => 'E',
        'Ẹ' => 'E',
        'Ȅ' => 'E',
        'È' => 'E',
        'Ẻ' => 'E',
        'Ȇ' => 'E',
        'Ē' => 'E',
        'Ḗ' => 'E',
        'Ḕ' => 'E',
        'Ę' => 'E',
        'Ɇ' => 'E',
        'Ẽ' => 'E',
        'Ḛ' => 'E',
        'Ꝫ' => 'ET',
        'Ḟ' => 'F',
        'Ƒ' => 'F',
        'Ǵ' => 'G',
        'Ğ' => 'G',
        'Ǧ' => 'G',
        'Ģ' => 'G',
        'Ĝ' => 'G',
        'Ġ' => 'G',
        'Ɠ' => 'G',
        'Ḡ' => 'G',
        'Ǥ' => 'G',
        'Ḫ' => 'H',
        'Ȟ' => 'H',
        'Ḩ' => 'H',
        'Ĥ' => 'H',
        'Ⱨ' => 'H',
        'Ḧ' => 'H',
        'Ḣ' => 'H',
        'Ḥ' => 'H',
        'Ħ' => 'H',
        'Í' => 'I',
        'Ĭ' => 'I',
        'Ǐ' => 'I',
        'Î' => 'I',
        'Ï' => 'I',
        'Ḯ' => 'I',
        'İ' => 'I',
        'Ị' => 'I',
        'Ȉ' => 'I',
        'Ì' => 'I',
        'Ỉ' => 'I',
        'Ȋ' => 'I',
        'Ī' => 'I',
        'Į' => 'I',
        'Ɨ' => 'I',
        'Ĩ' => 'I',
        'Ḭ' => 'I',
        'І' => 'I',
        'Ꝺ' => 'D',
        'Ꝼ' => 'F',
        'Ᵹ' => 'G',
        'Ꞃ' => 'R',
        'Ꞅ' => 'S',
        'Ꞇ' => 'T',
        'Ꝭ' => 'IS',
        'Ĵ' => 'J',
        'Ɉ' => 'J',
        'Ḱ' => 'K',
        'Ǩ' => 'K',
        'Ķ' => 'K',
        'Ⱪ' => 'K',
        'Ꝃ' => 'K',
        'Ḳ' => 'K',
        'Ƙ' => 'K',
        'Ḵ' => 'K',
        'Ꝁ' => 'K',
        'Ꝅ' => 'K',
        'Ĺ' => 'L',
        'Ƚ' => 'L',
        'Ľ' => 'L',
        'Ļ' => 'L',
        'Ḽ' => 'L',
        'Ḷ' => 'L',
        'Ḹ' => 'L',
        'Ⱡ' => 'L',
        'Ꝉ' => 'L',
        'Ḻ' => 'L',
        'Ŀ' => 'L',
        'Ɫ' => 'L',
        'ǈ' => 'L',
        'Ł' => 'L',
        'Ǉ' => 'LJ',
        'Ḿ' => 'M',
        'Ṁ' => 'M',
        'Ṃ' => 'M',
        'Ɱ' => 'M',
        'Ń' => 'N',
        'Ň' => 'N',
        'Ņ' => 'N',
        'Ṋ' => 'N',
        'Ṅ' => 'N',
        'Ṇ' => 'N',
        'Ǹ' => 'N',
        'Ɲ' => 'N',
        'Ṉ' => 'N',
        'Ƞ' => 'N',
        'ǋ' => 'N',
        'Ñ' => 'N',
        'Ǌ' => 'NJ',
        'Ó' => 'O',
        'Ŏ' => 'O',
        'Ǒ' => 'O',
        'Ô' => 'O',
        'Ố' => 'O',
        'Ộ' => 'O',
        'Ồ' => 'O',
        'Ổ' => 'O',
        'Ỗ' => 'O',
        'Ö' => 'O',
        'Ȫ' => 'O',
        'Ȯ' => 'O',
        'Ȱ' => 'O',
        'Ọ' => 'O',
        'Ő' => 'O',
        'Ȍ' => 'O',
        'Ò' => 'O',
        'Ỏ' => 'O',
        'Ơ' => 'O',
        'Ớ' => 'O',
        'Ợ' => 'O',
        'Ờ' => 'O',
        'Ở' => 'O',
        'Ỡ' => 'O',
        'Ȏ' => 'O',
        'Ꝋ' => 'O',
        'Ꝍ' => 'O',
        'Ō' => 'O',
        'Ṓ' => 'O',
        'Ṑ' => 'O',
        'Ɵ' => 'O',
        'Ǫ' => 'O',
        'Ǭ' => 'O',
        'Ø' => 'O',
        'Ǿ' => 'O',
        'Õ' => 'O',
        'Ṍ' => 'O',
        'Ṏ' => 'O',
        'Ȭ' => 'O',
        'Ƣ' => 'OI',
        'Ꝏ' => 'OO',
        'Ɛ' => 'E',
        'Ɔ' => 'O',
        'Ȣ' => 'OU',
        'Ṕ' => 'P',
        'Ṗ' => 'P',
        'Ꝓ' => 'P',
        'Ƥ' => 'P',
        'Ꝕ' => 'P',
        'Ᵽ' => 'P',
        'Ꝑ' => 'P',
        'Ꝙ' => 'Q',
        'Ꝗ' => 'Q',
        'Ŕ' => 'R',
        'Ř' => 'R',
        'Ŗ' => 'R',
        'Ṙ' => 'R',
        'Ṛ' => 'R',
        'Ṝ' => 'R',
        'Ȑ' => 'R',
        'Ȓ' => 'R',
        'Ṟ' => 'R',
        'Ɍ' => 'R',
        'Ɽ' => 'R',
        'Ꜿ' => 'C',
        'Ǝ' => 'E',
        'Ś' => 'S',
        'Ṥ' => 'S',
        'Š' => 'S',
        'Ṧ' => 'S',
        'Ş' => 'S',
        'Ŝ' => 'S',
        'Ș' => 'S',
        'Ṡ' => 'S',
        'Ṣ' => 'S',
        'Ṩ' => 'S',
        'ß' => 'ss',
        'Ť' => 'T',
        'Ţ' => 'T',
        'Ṱ' => 'T',
        'Ț' => 'T',
        'Ⱦ' => 'T',
        'Ṫ' => 'T',
        'Ṭ' => 'T',
        'Ƭ' => 'T',
        'Ṯ' => 'T',
        'Ʈ' => 'T',
        'Ŧ' => 'T',
        'Ɐ' => 'A',
        'Ꞁ' => 'L',
        'Ɯ' => 'M',
        'Ʌ' => 'V',
        'Ꜩ' => 'TZ',
        'Ú' => 'U',
        'Ŭ' => 'U',
        'Ǔ' => 'U',
        'Û' => 'U',
        'Ṷ' => 'U',
        'Ü' => 'U',
        'Ǘ' => 'U',
        'Ǚ' => 'U',
        'Ǜ' => 'U',
        'Ǖ' => 'U',
        'Ṳ' => 'U',
        'Ụ' => 'U',
        'Ű' => 'U',
        'Ȕ' => 'U',
        'Ù' => 'U',
        'Ủ' => 'U',
        'Ư' => 'U',
        'Ứ' => 'U',
        'Ự' => 'U',
        'Ừ' => 'U',
        'Ử' => 'U',
        'Ữ' => 'U',
        'Ȗ' => 'U',
        'Ū' => 'U',
        'Ṻ' => 'U',
        'Ų' => 'U',
        'Ů' => 'U',
        'Ũ' => 'U',
        'Ṹ' => 'U',
        'Ṵ' => 'U',
        'Ꝟ' => 'V',
        'Ṿ' => 'V',
        'Ʋ' => 'V',
        'Ṽ' => 'V',
        'Ꝡ' => 'VY',
        'Ẃ' => 'W',
        'Ŵ' => 'W',
        'Ẅ' => 'W',
        'Ẇ' => 'W',
        'Ẉ' => 'W',
        'Ẁ' => 'W',
        'Ⱳ' => 'W',
        'Ẍ' => 'X',
        'Ẋ' => 'X',
        'Ý' => 'Y',
        'Ŷ' => 'Y',
        'Ÿ' => 'Y',
        'Ẏ' => 'Y',
        'Ỵ' => 'Y',
        'Ỳ' => 'Y',
        'Ƴ' => 'Y',
        'Ỷ' => 'Y',
        'Ỿ' => 'Y',
        'Ȳ' => 'Y',
        'Ɏ' => 'Y',
        'Ỹ' => 'Y',
        'Ї' => 'YI',
        'Ź' => 'Z',
        'Ž' => 'Z',
        'Ẑ' => 'Z',
        'Ⱬ' => 'Z',
        'Ż' => 'Z',
        'Ẓ' => 'Z',
        'Ȥ' => 'Z',
        'Ẕ' => 'Z',
        'Ƶ' => 'Z',
        'Þ' => 'TH',
        'Ĳ' => 'IJ',
        'Œ' => 'OE',
        'ᴀ' => 'A',
        'ᴁ' => 'AE',
        'ʙ' => 'B',
        'ᴃ' => 'B',
        'ᴄ' => 'C',
        'ᴅ' => 'D',
        'ᴇ' => 'E',
        'ꜰ' => 'F',
        'ɢ' => 'G',
        'ʛ' => 'G',
        'ʜ' => 'H',
        'ɪ' => 'I',
        'ʁ' => 'R',
        'ᴊ' => 'J',
        'ᴋ' => 'K',
        'ʟ' => 'L',
        'ᴌ' => 'L',
        'ᴍ' => 'M',
        'ɴ' => 'N',
        'ᴏ' => 'O',
        'ɶ' => 'OE',
        'ᴐ' => 'O',
        'ᴕ' => 'OU',
        'ᴘ' => 'P',
        'ʀ' => 'R',
        'ᴎ' => 'N',
        'ᴙ' => 'R',
        'ꜱ' => 'S',
        'ᴛ' => 'T',
        'ⱻ' => 'E',
        'ᴚ' => 'R',
        'ᴜ' => 'U',
        'ᴠ' => 'V',
        'ᴡ' => 'W',
        'ʏ' => 'Y',
        'ᴢ' => 'Z',
        'á' => 'a',
        'ă' => 'a',
        'ắ' => 'a',
        'ặ' => 'a',
        'ằ' => 'a',
        'ẳ' => 'a',
        'ẵ' => 'a',
        'ǎ' => 'a',
        'â' => 'a',
        'ấ' => 'a',
        'ậ' => 'a',
        'ầ' => 'a',
        'ẩ' => 'a',
        'ẫ' => 'a',
        'ä' => 'a',
        'ǟ' => 'a',
        'ȧ' => 'a',
        'ǡ' => 'a',
        'ạ' => 'a',
        'ȁ' => 'a',
        'à' => 'a',
        'ả' => 'a',
        'ȃ' => 'a',
        'ā' => 'a',
        'ą' => 'a',
        'ᶏ' => 'a',
        'ẚ' => 'a',
        'å' => 'a',
        'ǻ' => 'a',
        'ḁ' => 'a',
        'ⱥ' => 'a',
        'ã' => 'a',
        'ꜳ' => 'aa',
        'æ' => 'ae',
        'ǽ' => 'ae',
        'ǣ' => 'ae',
        'ꜵ' => 'ao',
        'ꜷ' => 'au',
        'ꜹ' => 'av',
        'ꜻ' => 'av',
        'ꜽ' => 'ay',
        'ḃ' => 'b',
        'ḅ' => 'b',
        'ɓ' => 'b',
        'ḇ' => 'b',
        'ᵬ' => 'b',
        'ᶀ' => 'b',
        'ƀ' => 'b',
        'ƃ' => 'b',
        'ɵ' => 'o',
        'ć' => 'c',
        'č' => 'c',
        'ç' => 'c',
        'ḉ' => 'c',
        'ĉ' => 'c',
        'ɕ' => 'c',
        'ċ' => 'c',
        'ƈ' => 'c',
        'ȼ' => 'c',
        'ď' => 'd',
        'ḑ' => 'd',
        'ḓ' => 'd',
        'ȡ' => 'd',
        'ḋ' => 'd',
        'ḍ' => 'd',
        'ɗ' => 'd',
        'ᶑ' => 'd',
        'ḏ' => 'd',
        'ᵭ' => 'd',
        'ᶁ' => 'd',
        'đ' => 'd',
        'ɖ' => 'd',
        'ƌ' => 'd',
        'ð' => 'd',
        'ı' => 'i',
        'ȷ' => 'j',
        'ɟ' => 'j',
        'ʄ' => 'j',
        'ǳ' => 'dz',
        'ǆ' => 'dz',
        'é' => 'e',
        'ĕ' => 'e',
        'ě' => 'e',
        'ȩ' => 'e',
        'ḝ' => 'e',
        'ê' => 'e',
        'ế' => 'e',
        'ệ' => 'e',
        'ề' => 'e',
        'ể' => 'e',
        'ễ' => 'e',
        'ḙ' => 'e',
        'ë' => 'e',
        'ė' => 'e',
        'ẹ' => 'e',
        'ȅ' => 'e',
        'è' => 'e',
        'ẻ' => 'e',
        'ȇ' => 'e',
        'ē' => 'e',
        'ḗ' => 'e',
        'ḕ' => 'e',
        'ⱸ' => 'e',
        'ę' => 'e',
        'ᶒ' => 'e',
        'ɇ' => 'e',
        'ẽ' => 'e',
        'ḛ' => 'e',
        'ꝫ' => 'et',
        'ḟ' => 'f',
        'ƒ' => 'f',
        'ᵮ' => 'f',
        'ᶂ' => 'f',
        'ǵ' => 'g',
        'ğ' => 'g',
        'ǧ' => 'g',
        'ģ' => 'g',
        'ĝ' => 'g',
        'ġ' => 'g',
        'ɠ' => 'g',
        'ḡ' => 'g',
        'ᶃ' => 'g',
        'ǥ' => 'g',
        'ḫ' => 'h',
        'ȟ' => 'h',
        'ḩ' => 'h',
        'ĥ' => 'h',
        'ⱨ' => 'h',
        'ḧ' => 'h',
        'ḣ' => 'h',
        'ḥ' => 'h',
        'ɦ' => 'h',
        'ẖ' => 'h',
        'ħ' => 'h',
        'ƕ' => 'hv',
        'í' => 'i',
        'ĭ' => 'i',
        'ǐ' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ḯ' => 'i',
        'ị' => 'i',
        'ȉ' => 'i',
        'ì' => 'i',
        'ỉ' => 'i',
        'ȋ' => 'i',
        'ī' => 'i',
        'į' => 'i',
        'ᶖ' => 'i',
        'ɨ' => 'i',
        'ĩ' => 'i',
        'ḭ' => 'i',
        'і' => 'i',
        'ꝺ' => 'd',
        'ꝼ' => 'f',
        'ᵹ' => 'g',
        'ꞃ' => 'r',
        'ꞅ' => 's',
        'ꞇ' => 't',
        'ꝭ' => 'is',
        'ǰ' => 'j',
        'ĵ' => 'j',
        'ʝ' => 'j',
        'ɉ' => 'j',
        'ḱ' => 'k',
        'ǩ' => 'k',
        'ķ' => 'k',
        'ⱪ' => 'k',
        'ꝃ' => 'k',
        'ḳ' => 'k',
        'ƙ' => 'k',
        'ḵ' => 'k',
        'ᶄ' => 'k',
        'ꝁ' => 'k',
        'ꝅ' => 'k',
        'ĺ' => 'l',
        'ƚ' => 'l',
        'ɬ' => 'l',
        'ľ' => 'l',
        'ļ' => 'l',
        'ḽ' => 'l',
        'ȴ' => 'l',
        'ḷ' => 'l',
        'ḹ' => 'l',
        'ⱡ' => 'l',
        'ꝉ' => 'l',
        'ḻ' => 'l',
        'ŀ' => 'l',
        'ɫ' => 'l',
        'ᶅ' => 'l',
        'ɭ' => 'l',
        'ł' => 'l',
        'ǉ' => 'lj',
        'ſ' => 's',
        'ẜ' => 's',
        'ẛ' => 's',
        'ẝ' => 's',
        'ḿ' => 'm',
        'ṁ' => 'm',
        'ṃ' => 'm',
        'ɱ' => 'm',
        'ᵯ' => 'm',
        'ᶆ' => 'm',
        'ń' => 'n',
        'ň' => 'n',
        'ņ' => 'n',
        'ṋ' => 'n',
        'ȵ' => 'n',
        'ṅ' => 'n',
        'ṇ' => 'n',
        'ǹ' => 'n',
        'ɲ' => 'n',
        'ṉ' => 'n',
        'ƞ' => 'n',
        'ᵰ' => 'n',
        'ᶇ' => 'n',
        'ɳ' => 'n',
        'ñ' => 'n',
        'ǌ' => 'nj',
        'ó' => 'o',
        'ŏ' => 'o',
        'ǒ' => 'o',
        'ô' => 'o',
        'ố' => 'o',
        'ộ' => 'o',
        'ồ' => 'o',
        'ổ' => 'o',
        'ỗ' => 'o',
        'ö' => 'o',
        'ȫ' => 'o',
        'ȯ' => 'o',
        'ȱ' => 'o',
        'ọ' => 'o',
        'ő' => 'o',
        'ȍ' => 'o',
        'ò' => 'o',
        'ỏ' => 'o',
        'ơ' => 'o',
        'ớ' => 'o',
        'ợ' => 'o',
        'ờ' => 'o',
        'ở' => 'o',
        'ỡ' => 'o',
        'ȏ' => 'o',
        'ꝋ' => 'o',
        'ꝍ' => 'o',
        'ⱺ' => 'o',
        'ō' => 'o',
        'ṓ' => 'o',
        'ṑ' => 'o',
        'ǫ' => 'o',
        'ǭ' => 'o',
        'ø' => 'o',
        'ǿ' => 'o',
        'õ' => 'o',
        'ṍ' => 'o',
        'ṏ' => 'o',
        'ȭ' => 'o',
        'ƣ' => 'oi',
        'ꝏ' => 'oo',
        'ɛ' => 'e',
        'ᶓ' => 'e',
        'ɔ' => 'o',
        'ᶗ' => 'o',
        'ȣ' => 'ou',
        'ṕ' => 'p',
        'ṗ' => 'p',
        'ꝓ' => 'p',
        'ƥ' => 'p',
        'ᵱ' => 'p',
        'ᶈ' => 'p',
        'ꝕ' => 'p',
        'ᵽ' => 'p',
        'ꝑ' => 'p',
        'ꝙ' => 'q',
        'ʠ' => 'q',
        'ɋ' => 'q',
        'ꝗ' => 'q',
        'ŕ' => 'r',
        'ř' => 'r',
        'ŗ' => 'r',
        'ṙ' => 'r',
        'ṛ' => 'r',
        'ṝ' => 'r',
        'ȑ' => 'r',
        'ɾ' => 'r',
        'ᵳ' => 'r',
        'ȓ' => 'r',
        'ṟ' => 'r',
        'ɼ' => 'r',
        'ᵲ' => 'r',
        'ᶉ' => 'r',
        'ɍ' => 'r',
        'ɽ' => 'r',
        'ↄ' => 'c',
        'ꜿ' => 'c',
        'ɘ' => 'e',
        'ɿ' => 'r',
        'ś' => 's',
        'ṥ' => 's',
        'š' => 's',
        'ṧ' => 's',
        'ş' => 's',
        'ŝ' => 's',
        'ș' => 's',
        'ṡ' => 's',
        'ṣ' => 's',
        'ṩ' => 's',
        'ʂ' => 's',
        'ᵴ' => 's',
        'ᶊ' => 's',
        'ȿ' => 's',
        'ɡ' => 'g',
        'ᴑ' => 'o',
        'ᴓ' => 'o',
        'ᴝ' => 'u',
        'ť' => 't',
        'ţ' => 't',
        'ṱ' => 't',
        'ț' => 't',
        'ȶ' => 't',
        'ẗ' => 't',
        'ⱦ' => 't',
        'ṫ' => 't',
        'ṭ' => 't',
        'ƭ' => 't',
        'ṯ' => 't',
        'ᵵ' => 't',
        'ƫ' => 't',
        'ʈ' => 't',
        'ŧ' => 't',
        'ᵺ' => 'th',
        'ɐ' => 'a',
        'ᴂ' => 'ae',
        'ǝ' => 'e',
        'ᵷ' => 'g',
        'ɥ' => 'h',
        'ʮ' => 'h',
        'ʯ' => 'h',
        'ᴉ' => 'i',
        'ʞ' => 'k',
        'ꞁ' => 'l',
        'ɯ' => 'm',
        'ɰ' => 'm',
        'ᴔ' => 'oe',
        'ɹ' => 'r',
        'ɻ' => 'r',
        'ɺ' => 'r',
        'ⱹ' => 'r',
        'ʇ' => 't',
        'ʌ' => 'v',
        'ʍ' => 'w',
        'ʎ' => 'y',
        'ꜩ' => 'tz',
        'ú' => 'u',
        'ŭ' => 'u',
        'ǔ' => 'u',
        'û' => 'u',
        'ṷ' => 'u',
        'ü' => 'u',
        'ǘ' => 'u',
        'ǚ' => 'u',
        'ǜ' => 'u',
        'ǖ' => 'u',
        'ṳ' => 'u',
        'ụ' => 'u',
        'ű' => 'u',
        'ȕ' => 'u',
        'ù' => 'u',
        'ủ' => 'u',
        'ư' => 'u',
        'ứ' => 'u',
        'ự' => 'u',
        'ừ' => 'u',
        'ử' => 'u',
        'ữ' => 'u',
        'ȗ' => 'u',
        'ū' => 'u',
        'ṻ' => 'u',
        'ų' => 'u',
        'ᶙ' => 'u',
        'ů' => 'u',
        'ũ' => 'u',
        'ṹ' => 'u',
        'ṵ' => 'u',
        'ᵫ' => 'ue',
        'ꝸ' => 'um',
        'ⱴ' => 'v',
        'ꝟ' => 'v',
        'ṿ' => 'v',
        'ʋ' => 'v',
        'ᶌ' => 'v',
        'ⱱ' => 'v',
        'ṽ' => 'v',
        'ꝡ' => 'vy',
        'ẃ' => 'w',
        'ŵ' => 'w',
        'ẅ' => 'w',
        'ẇ' => 'w',
        'ẉ' => 'w',
        'ẁ' => 'w',
        'ⱳ' => 'w',
        'ẘ' => 'w',
        'ẍ' => 'x',
        'ẋ' => 'x',
        'ᶍ' => 'x',
        'ý' => 'y',
        'ŷ' => 'y',
        'ÿ' => 'y',
        'ẏ' => 'y',
        'ỵ' => 'y',
        'ỳ' => 'y',
        'ƴ' => 'y',
        'ỷ' => 'y',
        'ỿ' => 'y',
        'ȳ' => 'y',
        'ẙ' => 'y',
        'ɏ' => 'y',
        'ỹ' => 'y',
        'ї' => 'yi',
        'ź' => 'z',
        'ž' => 'z',
        'ẑ' => 'z',
        'ʑ' => 'z',
        'ⱬ' => 'z',
        'ż' => 'z',
        'ẓ' => 'z',
        'ȥ' => 'z',
        'ẕ' => 'z',
        'ᵶ' => 'z',
        'ᶎ' => 'z',
        'ʐ' => 'z',
        'ƶ' => 'z',
        'ɀ' => 'z',
        'þ' => 'th',
        'ﬀ' => 'ff',
        'ﬃ' => 'ffi',
        'ﬄ' => 'ffl',
        'ﬁ' => 'fi',
        'ﬂ' => 'fl',
        'ĳ' => 'ij',
        'œ' => 'oe',
        'ﬆ' => 'st',
        'ₐ' => 'a',
        'ₑ' => 'e',
        'ᵢ' => 'i',
        'ⱼ' => 'j',
        'ₒ' => 'o',
        'ᵣ' => 'r',
        'ᵤ' => 'u',
        'ᵥ' => 'v',
        'ₓ' => 'x',
        'Ё' => 'YO',
        'Й' => 'I',
        'Ц' => 'TS',
        'У' => 'U',
        'К' => 'K',
        'Е' => 'E',
        'Н' => 'N',
        'Г' => 'G',
        'Ґ' => 'G',
        'Ш' => 'SH',
        'Щ' => 'SCH',
        'З' => 'Z',
        'Х' => 'H',
        'Ъ' => "'",
        'ё' => 'yo',
        'й' => 'i',
        'ц' => 'ts',
        'у' => 'u',
        'к' => 'k',
        'е' => 'e',
        'н' => 'n',
        'г' => 'g',
        'ґ' => 'g',
        'ш' => 'sh',
        'щ' => 'sch',
        'з' => 'z',
        'х' => 'h',
        'ъ' => "'",
        'Ф' => 'F',
        'Ы' => 'I',
        'В' => 'V',
        'А' => 'a',
        'П' => 'P',
        'Р' => 'R',
        'О' => 'O',
        'Л' => 'L',
        'Д' => 'D',
        'Ж' => 'ZH',
        'Э' => 'E',
        'ф' => 'f',
        'ы' => 'i',
        'в' => 'v',
        'а' => 'a',
        'п' => 'p',
        'р' => 'r',
        'о' => 'o',
        'л' => 'l',
        'д' => 'd',
        'ж' => 'zh',
        'э' => 'e',
        'Я' => 'Ya',
        'Ч' => 'CH',
        'С' => 'S',
        'М' => 'M',
        'И' => 'I',
        'Т' => 'T',
        'Ь' => "'",
        'Б' => 'B',
        'Ю' => 'YU',
        'я' => 'ya',
        'ч' => 'ch',
        'с' => 's',
        'м' => 'm',
        'и' => 'i',
        'т' => 't',
        'ь' => "'",
        'б' => 'b',
        'ю' => 'yu'
    ];
    $str = str_replace(array_keys($latin), array_values($latin), $str);
    $str = str_replace(' ', '-', $str);
    $str = preg_replace('/[-]+/', '-', $str);
    echo "<b>" . $str . "</b>";
    echo "<br>";
    return $str;
}

function translate($id, $fr, $en, $it, $ar)
{
    $fr["product"]["pro-" . $id] = [
        "title" => $_POST["title_fr"],
        "desc" => $_POST["desc_fr"],
        "description" => $_POST["description_fr"]
    ];
    $en["product"]["pro-" . $id] = [
        "title" => $_POST["title_en"],
        "desc" => $_POST["desc_en"],
        "description" => $_POST["description_en"]
    ];
    $it["product"]["pro-" . $id] = [
        "title" => $_POST["title_it"],
        "desc" => $_POST["desc_it"],
        "description" => $_POST["description_it"]
    ];
    $ar["product"]["pro-" . $id] = [
        "title" => $_POST["title_ar"],
        "desc" => $_POST["desc_ar"],
        "description" => $_POST["description_ar"]
    ];
    file_put_contents("../translate/data-fr.json", json_encode($fr), true);
    file_put_contents("../translate/data-en.json", json_encode($en), true);
    file_put_contents("../translate/data-it.json", json_encode($it), true);
    file_put_contents("../translate/data-ar.json", json_encode($ar), true);
}

function product($id, $img, $images, $products)
{
    array_unshift($images, $img);
    array_push($products, [
        "path" => "/products/" . _clean($_POST["title_fr"]) . ".html",
        "image" => $img,
        "images" => implode("|", $images),
        "title" => $_POST["title_fr"],
        "category" => $_POST["category"],
        "brand" => $_POST["brand"],
        "id" => $id
    ]);
    file_put_contents("../json/products.json", json_encode($products), true);
}

function upload($file)
{
    // Exit if is not a valid image file
    $image_type = exif_imagetype($file["tmp_name"]);
    // Get file extension based on file type, to prepend a dot we pass true as the second parameter
    $image_extension = image_type_to_extension($image_type, true);
    // Create a unique image name
    $image_name = bin2hex(random_bytes(16)) . $image_extension;
    // Move the temp image file to the images directory
    move_uploaded_file(
        // Temp image location
        $file["tmp_name"],
        // New image location
        "../assets/products/" . $image_name
    );
    return "/assets/products/" . $image_name;
}

function uploads($file)
{
    $images = [];
    if (!empty(array_filter($file['name']))) {
        for ($i = 0; $i < count($file['name']); $i++) {
            // Exit if is not a valid image file
            $image_type = exif_imagetype($file["tmp_name"][$i]);
            // Get file extension based on file type, to prepend a dot we pass true as the second parameter
            $image_extension = image_type_to_extension($image_type, true);
            // Create a unique image name
            $image_name = bin2hex(random_bytes(16)) . $image_extension;
            // Move the temp image file to the images directory
            move_uploaded_file(
                // Temp image location
                $file["tmp_name"][$i],
                // New image location
                "../assets/products/" . $image_name
            );
            array_push($images, "/assets/products/" . $image_name);
        }
    }
    return $images;
}

if (isset($_POST['send'])) {
    $image = upload($_FILES['primary']);
    $images = uploads($_FILES['extra']);
    translate($id, $fr, $en, $it, $ar);
    product($id, $image, $images, $products);
    $myfile = fopen("../products/" . _clean($_POST['title_fr']) . ".html", "w+") or die("Unable to open file!");
    $IMG = '<button onclick="preview(this, `#preview`)" class="w-24 h-24 border border-gray-200 shadow-sm rounded-lg bg-white p-2 flex items-center justify-center cursor-pointer">
        <img src="' . $image . '" class="block max-w-full max-h-full pointer-events-none" />
    </button>';
    foreach ($images as $img) {
        $IMG .= '<button onclick="preview(this, `#preview`)" class="w-24 h-24 border border-gray-200 shadow-sm rounded-lg bg-white p-2 flex items-center justify-center cursor-pointer">
                <img src="' . $img . '" class="block max-w-full max-h-full pointer-events-none" />
            </button>';
    }
    $info = '';
    if (strlen($_POST["description_fr"]) > 0) {
        $info = '
            <div class="flex flex-col gap-4 lg:gap-8 container mx-auto my-6 lg:my-10">
                <h1 data-translate-id="extra.desc" class="text-2xl lg:text-4xl text-center">
                        Caractéristiques
                </h1>
                <div data-translate-id="product.pro-' . $id . '.description" class="block w-full reset">
                    ' . $_POST["description_fr"] . '
                </div>
            </div>
        ';
    }
    $code = '
    <!DOCTYPE html>
    <html lang="fr" class="w-full overflow-x-hidden">
    
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://cdn.tailwindcss.com/"></script>
        <script src="/assets/js/app.js"></script>
        <link rel="stylesheet" href="/assets/css/style.css" />
        <title data-translate-id="product.pro-' . $id . '.title">' . $_POST["title_fr"] . '</title>
    </head>
    
    <body class="text-gray-800 pb-4 lg:pt-4">
       ' . $HEADER . '
        ' . $SLIDE . '
        <section class="flex flex-wrap gap-2 items-center mx-auto container p-4">
            <a href="/index.html" data-translate-id="menu.home" class="block">ACCUEIL</a>
            <span class="block border border-gray-900 w-2"></span>
            <a href="/products.html" data-translate-id="header.category" class="block">NOS GAMMES</a>
            <span class="block border border-gray-900 w-2"></span>
            <a href="/categories/' . strtolower(clean($_POST['category'])) . '.html" data-translate-id="' . array_search($_POST['category'], $categories) . '" class="block">' . $_POST["category"] . '</a>
            <span class="block border border-gray-900 w-2"></span>
            <a href="/products/' . _clean($_POST['title_fr']) . '.html" class="block text-prime" data-translate-id="product.pro-' . $id . '.title">' . $_POST["title_fr"] . '</a>
        </section>
        <main class="overflow-x-hidden flex flex-col lg:flex-row lg:flex-wrap gap-4 p-4 my-10 container mx-auto items-start">
            <div class="w-full lg:w-1/4 flex flex-col gap-4">
                <div class="w-full text-white items-center justify-between flex gap-4 lg:hidden rounded-2xl bg-grade px-4 py-2">
                    <h1 data-translate-id="' . array_search($_POST['category'], $categories) . '" class="font-semibold text-xl">' . $_POST["category"] . '</h1>
                    <button onclick="show(`#aside`)" class="block">
                        <svg class="block h-6 w-6 pointer-events-none" fill="currentcolor" viewBox="0 0 48 48">
                            <path d="M21.05 43V30.75h4v4.2h17.7v4h-17.7V43Zm-15.8-4.05v-4h13.3v4Zm9.3-8.9v-4.1h-9.3V22h9.3v-4.15h4v12.2Zm6.5-4.1V22h21.7v3.95Zm8.4-8.75V4.95h4v4.1h9.3v4h-9.3v4.15Zm-24.2-4.15v-4h21.7v4Z" />
                        </svg>
                    </button>
                </div>
                ' . $SIDE . '
            </div>
            <section id="print" class="flex flex-col lg:flex-row lg:flex-wrap gap-4 w-full lg:w-0 lg:flex-1">
                <div class="w-full lg:w-96 flex flex-col gap-2">
                    <picture class="w-full h-96 border border-gray-200 shadow-sm rounded-2xl bg-white p-4 flex items-center justify-center">
                        <img id="preview" src="' . $image . '" class="block max-w-full max-h-full" />
                    </picture>
                    <div class="w-full overflow-x-auto scroll">
                        <div class="flex flex-wrap w-max gap-2">'  . $IMG . '</div>
                    </div>
                </div>
                <div class="w-full lg:flex-1 flex flex-col gap-2">
                    <span class="text-lg font-bold text-gray-500">' . $_POST["ref"] . '</span>
                    <h1 class="text-4xl font-semibold mb-2" data-translate-id="product.pro-' . $id . '.title">
                        ' . $_POST["title_fr"] . '
                    </h1>
                    <div class="text-md text-gray-500 border-b border-gray-300 pb-2 mb-2" data-translate-id="product.pro-' . $id . '.desc">
                        ' . $_POST["desc_fr"] . '
                    </div>
                    <div class="flex justify-between lg:items-center flex-col lg:flex-row gap-4">
                        <div class="flex flex-col">
                            <h1 data-translate-id="extra.brand" class="text-md text-gray-900">Marque</h1>
                            <img src="/assets/brands/' . $_POST["brand"] . '.png" class="block max-60" />
                        </div>
                        <button data-translate-id="extra.file" onclick="_print(event)" class="text-sm bg-prime px-4 py-2 w-max rounded-2xl text-white">Télécharger la fiche produit</button>
                    </div>
                </div>
               ' . $info . '
            </section>
        </main>
        ' . $FOOTER . '
        <script defer>
            Starter.add(
                () => {
                    Element.Aside();
                    Element.Header();
                    Element.Slider(true);
                    Element.Footer(true);
                }
            ).init();
        </script>
        <script src="/assets/js/translate.js" defer></script>
        <div id="overlay" class="fixed inset-0 bg-white" style="z-index: 99999;"></div>
    </body>
    
    </html>
    ';
    fwrite($myfile, $code);
    fclose($myfile);
    header("location: /a/list.php");
}
?>

<!DOCTYPE html>
<html lang="fr" class="w-full overflow-x-hidden">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com/"></script>
    <link rel="stylesheet" href="/assets/css/style.css" />
    <script src="https://cdn.tiny.cloud/1/5q3f1hswxbi1wr1hsf6r4che0wgz79s909nh2cl0vwv81983/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <title>CREER PRODUIT</title>
</head>

<body class="text-gray-800 py-4">
    <header class="w-full flex-col lg:flex-row gap-4 flex container mx-auto px-4 items-center justify-between">
        <a href="/index.html" class="w-32">
            <img src="/assets/logo.png" alt="logo" class="w-full block" />
        </a>
        <nav class="w-full lg:w-max">
            <ul class="flex flex-col w-full lg:flex-row gap-4 items-center">
                <li>
                    <a href="/a/list.php" class="w-max flex items-center gap-2 text-sm font-semibold hover:text-prime">
                        <span>Liste</span>
                    </a>
                </li>
                <li>
                    <a href="/a/create.php" class="w-max flex items-center gap-2 text-sm font-semibold hover:text-prime">
                        <span>Creer</span>
                    </a>
                </li>
                <li>
                    <a href="<?= $_SERVER['PHP_SELF']; ?>?logout=true" class="w-max flex items-center gap-2 text-sm font-semibold text-white bg-prime rounded-md px-4 py-2 bg-prime bg-grade">
                        <span>Deconnexion</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <main class="overflow-x-hidden">
        <form class="flex flex-col gap-4 container mx-auto px-4 items-end mt-10" method="post" action="<?= $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <div class="w-full">
                <div class="flex flex-1 flex-col">
                    <label for="ref" class="text-gray-900 font-black text-md">Reference</label>
                    <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                        <input type="text" id="ref" name="ref" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Reference" required />
                    </div>
                </div>
                <div class="flex flex-1 flex-col">
                    <label for="category" class="text-gray-900 font-black text-md">Categorie</label>
                    <select data-format-select placeholder="Categorie" name="category" id="category" class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden flex items-center px-4">
                        <?php foreach ($categories as $key => $value) { ?>
                            <option value="<?= $value ?>"><?= $value ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="flex flex-1 flex-col">
                    <label for="brand" class="text-gray-900 font-black text-md">Marque</label>
                    <select data-format-select placeholder="Marque" name="brand" id="brand" class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden flex items-center px-4">
                        <option value="BRAND-LESS">BRANDLESS</option>
                        <?php foreach ($brands as $value) { ?>
                            <option value="<?= clean($value) ?>"><?= $value ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="w-full">
                <h1 class="font-black text-2xl">Images</h1>
                <div class="flex flex-1 flex-col">
                    <label for="primary" class="text-gray-900 font-black text-md">Principale</label>
                    <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden flex items-center px-4">
                        <input type="file" id="primary" name="primary" class="text-gray-900 text-lg flex-1 bg-transparent focus:outline focus:outline-2 outline-green-500" placeholder="Principale" required />
                    </div>
                </div>
                <div class="flex flex-1 flex-col">
                    <label for="extra" class="text-gray-900 font-black text-md">Extra</label>
                    <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden flex items-center px-4">
                        <input type="file" id="extra" name="extra[]" class="text-gray-900 text-lg flex-1 bg-transparent focus:outline focus:outline-2 outline-green-500" placeholder="Extra" multiple />
                    </div>
                </div>
            </div>
            <div class="w-full">
                <h1 class="font-black text-2xl">Titre</h1>
                <div class="w-full flex flex-col md:flex-row gap-4">
                    <div class="flex flex-1 flex-col">
                        <label for="title_fr" class="text-gray-900 font-black text-md">Francais</label>
                        <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                            <input type="text" id="title_fr" name="title_fr" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Francais" required />
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col">
                        <label for="title_en" class="text-gray-900 font-black text-md">Anglais</label>
                        <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                            <input type="text" id="title_en" name="title_en" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Anglais" required />
                        </div>
                    </div>
                </div>
                <div class="w-full flex flex-col md:flex-row gap-4">
                    <div class="flex flex-1 flex-col">
                        <label for="title_it" class="text-gray-900 font-black text-md">Italian</label>
                        <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                            <input type="text" id="title_it" name="title_it" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Italian" required />
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col">
                        <label for="title_ar" class="text-gray-900 font-black text-md">Arabe</label>
                        <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                            <input type="text" id="title_ar" name="title_ar" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Arabe" required />
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <h1 class="font-black text-2xl">Mini Description</h1>
                <div class="w-full flex flex-col md:flex-row gap-4">
                    <div class="flex flex-1 flex-col">
                        <label for="desc_fr" class="text-gray-900 font-black text-md">Francais</label>
                        <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                            <input type="text" id="desc_fr" name="desc_fr" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Francais" />
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col">
                        <label for="desc_en" class="text-gray-900 font-black text-md">Anglais</label>
                        <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                            <input type="text" id="desc_en" name="desc_en" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Anglais" />
                        </div>
                    </div>
                </div>
                <div class="w-full flex flex-col md:flex-row gap-4">
                    <div class="flex flex-1 flex-col">
                        <label for="desc_it" class="text-gray-900 font-black text-md">Italian</label>
                        <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                            <input type="text" id="desc_it" name="desc_it" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Italian" />
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col">
                        <label for="desc_ar" class="text-gray-900 font-black text-md">Arabe</label>
                        <div class="w-full h-12 flex rounded-md bg-gray-100 overflow-hidden">
                            <input type="text" id="desc_ar" name="desc_ar" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Arabe" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <h1 class="font-black text-2xl">Description</h1>
                <div class="w-full flex flex-col">
                    <label for="description_fr" class="text-gray-900 font-black text-md">Francais</label>
                    <div class="w-full flex rounded-md bg-gray-100 overflow-hidden flex-col">
                        <textarea id="description_fr" name="description_fr" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Francais"></textarea>
                    </div>
                </div>
                <div class="w-full flex flex-col">
                    <label for="description_en" class="text-gray-900 font-black text-md">Anglais</label>
                    <div class="w-full flex rounded-md bg-gray-100 overflow-hidden flex-col">
                        <textarea id="description_en" name="description_en" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Anglais"></textarea>
                    </div>
                </div>
                <div class="w-full flex flex-col">
                    <label for="description_it" class="text-gray-900 font-black text-md">Italian</label>
                    <div class="w-full flex rounded-md bg-gray-100 overflow-hidden flex-col">
                        <textarea id="description_it" name="description_it" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Italian"></textarea>
                    </div>
                </div>
                <div class="w-full flex flex-col">
                    <label for="description_ar" class="text-gray-900 font-black text-md">Arabe</label>
                    <div class="w-full flex rounded-md bg-gray-100 overflow-hidden flex-col">
                        <textarea id="description_ar" name="description_ar" class="text-gray-900 p-4 text-lg flex-1 h-full bg-transparent rounded-md focus:outline focus:outline-2 outline-green-500" placeholder="Arabe"></textarea>
                    </div>
                </div>
            </div>
            <button name="send" type="submit" class="w-full h-12 flex items-center justify-center gap-2 p-4 rounded-md bg-green-500 focus:outline-none focus:bg-green-300 hover:bg-green-300">
                <span class="text-white font-black text-lg">ENREGISTRER</span>
            </button>
        </form>
    </main>
    <script>
        const config = {
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [{
                    value: 'First.Name',
                    title: 'First Name'
                },
                {
                    value: 'Email',
                    title: 'Email'
                },
            ]
        }
        tinymce.init({
            selector: '#description_fr',
            ...config
        });
        tinymce.init({
            selector: '#description_en',
            ...config
        });
        tinymce.init({
            selector: '#description_it',
            ...config
        });
        tinymce.init({
            selector: '#description_ar',
            ...config
        });
        const Select = function() {
            const targets = document.querySelectorAll("[data-format-select]:not(.format-select)");
            const classNames = {
                wrapper: "w-full relative",
                container: "border border-gray-200 hidden absolute left-0 right-0 flex flex-col bg-white rounded-md shadow-md overflow-auto z-50",
                input: "text-gray-900 p-4 w-full h-12 flex rounded-md bg-gray-100 text-md md:text-lg lg:text-xl 2xl:text-2xl focus:outline focus:outline-2 outline-green-500 cursor-pointer icon arrow",
                search: "text-gray-900 px-4 py-2 text-sm md:text-md lg:text-lg 2xl:text-xl bg-white focus:outline focus:outline-2 outline-green-500 rounded-t-md sticky top-0 border-b-2 border-gray-200",
                item: "text-left flex text-gray-900 px-4 py-2 text-md md:text-lg lg:text-xl 2xl:text-2xl bg-white focus:outline-none cursor-pointer focus:text-white focus:bg-green-500 hover:text-white hover:bg-green-500 rounded-md",
            }
            targets.forEach((target) => {
                target.classList.add("format-select")

                const wrapper = document.createElement("div"),
                    container = document.createElement("div"),
                    items = document.createElement("div"),
                    input = document.createElement("input"),
                    search = document.createElement("input");

                const toggle = function() {
                        container.classList.toggle("hidden");
                        container.classList.remove("top-full");
                        container.classList.remove("bottom-full");
                        var _ = "top-full";
                        if ((window.innerHeight - container.getBoundingClientRect().top) < container.clientHeight) _ = "bottom-full";
                        Array.from(container.querySelectorAll("button")).forEach(item => {
                            item.classList.remove("hidden");
                        });
                        search.value = "";
                        container.scrollTop = 0;
                        container.classList.add(_);
                        target.dispatchEvent(new CustomEvent('toggled', {
                            bubbles: true,
                        }))
                    },
                    select = function(i = 0, v) {
                        target.selectedIndex = i;
                        input.value = v;
                    },
                    clas = function($) {
                        container.childNodes.forEach(itm => {
                            itm.removeAttribute("style")
                        })
                        $.style.backgroundColor = "#dc2626";
                        $.style.color = "#FFFFFF";
                    },
                    run = function() {
                        if (target.options.length > 10) container.append(search);

                        Array.from(target.options).forEach((option, i) => {
                            if (option.disabled) return
                            const item = document.createElement("button");
                            item.className = classNames.item;
                            item.innerText = option.innerText.trim();
                            if (option.selected && !option.disabled) {
                                clas(item);
                                select(i, item.innerText);
                            }
                            item.addEventListener("click", e => {
                                e.preventDefault();
                                select(i, item.innerText);
                                toggle();
                                clas(item);
                                target.dispatchEvent(new CustomEvent('changed', {
                                    bubbles: true,
                                    detail: {
                                        item: item,
                                        index: i,
                                    }
                                }))
                            })
                            container.append(item);
                        })

                        target.dispatchEvent(new CustomEvent('loaded', {
                            bubbles: true,
                            detail: {
                                input: input,
                                search: search,
                            }
                        }))
                    };

                wrapper.className = classNames.wrapper;
                container.className = classNames.container;
                input.className = classNames.input;
                items.className = classNames.items;
                search.className = classNames.search;
                input.placeholder = target.getAttribute("placeholder") || "";
                input.setAttribute("readonly", true);
                input.id = target.id;
                target.removeAttribute("id");
                search.placeholder = "Search...";
                search.type = "search";
                container.style.maxHeight = "300px";
                wrapper.append(input);
                wrapper.append(container);

                const config = {
                    childList: true,
                    subtree: true
                };

                const observer = new MutationObserver(run);
                observer.observe(target, config);


                const selected = target.querySelector("option[selected]") ? "" : "selected"
                target.insertAdjacentHTML("afterbegin", "<option disabled " + selected + "></option>")

                search.addEventListener("input", e => {
                    const filter = e.target.value.toUpperCase();
                    Array.from(container.querySelectorAll("button")).forEach(item => {
                        const txt = item.textContent || txt.innerText;
                        if (txt && txt.toUpperCase().indexOf(filter) > -1) {
                            item.classList.remove("hidden");
                        } else {
                            item.classList.add("hidden");
                        }
                    });
                });

                input.addEventListener("click", toggle);
                target.classList.add("hidden");
                target.insertAdjacentElement("afterend", wrapper);
            });
        }
        Select();
    </script>
</body>

</html>