<?php
/**
 * Code Owner: CNIT
 * Executed By: PhongVu
 * Date: 11/25/2015
 * Time: 10:36 PM
 */
namespace CNIT;

final class CUtility
{
    public static function utf8Encoding($string) {
        $isUtf8 = false;
        $string = trim($string);
        $encodedStr = $string;
        if (function_exists("mb_check_encoding")) {
            $isUtf8 = mb_check_encoding($string, 'UTF-8');
        }
        else {
            /*preg_match('%^(?:
                  [\x09\x0A\x0D\x20-\x7E]            # ASCII
                | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
                |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
                | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
                |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
                |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
                | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
                |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
            )*$%xs', $string)*/
            $isUtf8 = preg_match('%^(?:
				          [\x09\x0A\x0D\x20-\x7E]
				        | [\xC2-\xDF][\x80-\xBF]
				        |  \xE0[\xA0-\xBF][\x80-\xBF]
				        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
				        |  \xED[\x80-\x9F][\x80-\xBF]
				        |  \xF0[\x90-\xBF][\x80-\xBF]{2}
				        | [\xF1-\xF3][\x80-\xBF]{3}
				        |  \xF4[\x80-\x8F][\x80-\xBF]{2}
				    )*$%xs', $string);
        }
        if($isUtf8 === false)
        {
            $encodedStr = utf8_encode($string);
        }
        return $encodedStr;
    }
}