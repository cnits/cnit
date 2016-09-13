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
	
	public static function segregateString($str) {
		//$str = preg_replace('/([a-z0-9])?([A-Z])/','$1-$2', $str);
        return trim(preg_replace("/([A-Z])/", " $0", $str));
	}

    public static function writeToCsv($path, array $data, $fields = array()) {
        $fcsv = fopen($path, "w") or die("Can't open file!");
        if (count($fields) === count($data[0])) {
            array_unshift($data, $fields);
        }
        foreach ($data as $row) {
            fputcsv($fcsv, $row);
        }
        fclose($fcsv);
    }

    public static function exportToCsv($fields = null, $array) {
        $out = "";
        foreach ($array as $arr) {
            //$out .= implode(",", $arr) . "\r\n";
            $vals = array();
            foreach ($arr as $key => $value) {
                $value = htmlspecialchars_decode($value);
                $value = str_replace('"', '""', $value);
                $value = '"' . $value . '"';
                $value = str_replace('–', '-', $value);
                $value = str_replace('“', '""', $value);
                $value = str_replace('”', '""', $value);
                $vals[] = $value;
            }
            $out .= implode(",", $vals) . "\r\n";
        }
        $strFields = "";
        if (!is_null($fields)) {
            $strFields = implode(",", $fields) . "\r\n";
        }
        return $strFields . $out;
    }

    public static function convertIndexed2AssociativeArray($keys, $array) {
        if (empty($keys) || empty($array)) {
            return null;
        }
        $results = array();
        if (is_array($array[0])) {
            foreach ($array as $item) {
                $results[] = array_combine($keys, $item);
            }
        } else {
            if (count($keys) === count($array)) {
                return array_combine($keys, $array);
            }
        }
        return $results;
    }

    public static function validateDateStr($str) {
        if (empty($str) || !is_string($str)) {
            return false;
        } else {
            $pattern1 = "/^((\d{4}\-\d{1,2}\-\d{1,2})|(\d{4}\/\d{1,2}\/\d{1,2})|(\d{1,2}\-\d{1,2}\-\d{4})|(\d{1,2}\/\d{1,2}\/\d{4}))( \d{2}\:\d{2})?$/";
            $pattern2 = "/^((\d{4}\-\d{1,2}\-\d{1,2})|(\d{4}\/\d{1,2}\/\d{1,2})|(\d{1,2}\-\d{1,2}\-\d{4})|(\d{1,2}\/\d{1,2}\/\d{4}))( \d{2}\:\d{2}\:\d{2})?$/";
            if (preg_match($pattern1, $str) || preg_match($pattern2, $str)) {
                try {
                    $date = new \DateTime($str, new \DateTimeZone("UTC"));
                    if ($date instanceof \DateTime) {
                        return true;
                    } else {
                        return false;
                    }
                } catch (\Exception $ex) {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public static function decodeHtmlSpecialCode($str) {
        if (!function_exists('htmlspecialchars_decode')) {
            function htmlspecialchars_decode($str) {
                return strtr($str, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
            }
        }
        return htmlspecialchars_decode($str);
    }
}