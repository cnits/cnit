<!DOCTYPE html>
<script charset="UTF-8" type="text/javascript" language="JavaScript" src="lib/built-in/amlich-js/JavaScript/amlich-hnd.js"></script>
<?php
	//echo '<div onload="showVietCal();">t</div>';
	class _Class {

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

        public $input = array(
            array("uID" => 1, "pID" => null),
            array("uID" => 2, "pID" => 1),
            array("uID" => 3, "pID" => 2),
            array("uID" => 14, "pID" => 3),
            array("uID" => 15, "pID" => 3),
            array("uID" => 16, "pID" => 3),
            array("uID" => 17, "pID" => 16),
            array("uID" => 18, "pID" => 16),
            array("uID" => 19, "pID" => 1),
        );

        public function recursive($pId = null, &$result = array()){
            foreach($this->input as $item){
                if($item["pID"] == $pId){
                    $result[] = $item;
                    self::recursive($item["uId"], $result);
                }
            }
        }
	}
	/*echo "<pre>";
	//var_dump(_Class::getQuarterOfDate('12-Jun-09', 2010));
	//$s = date_parse_from_format('m/d/Y', '2/14/2014');
	//var_dump(_Class::generateQuartersOfYear(2014, 6));

	$lastDate = new \DateTime('2014-09-22T08:30:00.610Z');
	$sDate = new \DateTime('2014-09-22T08:30:00.610Z');

	//$lastDate->sub(\DateInterval::createFromDateString('3 days'));
	$sDate -> setTimezone(new \DateTimeZone("America/Los_Angeles"));
	var_dump($lastDate->format('Y-m-d H:i:s'));
	var_dump($sDate->format('Y-m-d H:i:s'));
	var_dump(date_default_timezone_get());

	$test = new \DateTime();
	$test1 = new \DateTime();

	$test -> setTimezone(new \DateTimeZone("America/Los_Angeles"));

	var_dump($test -> format('Y/m/d H:i:s'));
	var_dump($test1 -> format('Y/m/d H:i:s'));

	$tz = new \DateTimeZone(date_default_timezone_get());
$tzUTC = new \DateTimeZone('UTC');

	$offset = $tzUTC -> getOffset($lastDate) - $tz -> getOffset($sDate);


$localTimeZone = new \DateTimeZone("America/Los_Angeles");
$localDate = new \DateTime('now', $localTimeZone);

$offset = $localTimeZone -> getOffset($localDate);
$offset = $offset/3600;
if($offset > 0){
	$_offset = '+0'.$offset.':00';
}else{
	$offset = $offset*(-1);
	$_offset = '-0'.$offset.':00';
}
var_dump($_offset);
$cmd = 'date +%z';
var_dump(exec($cmd));


echo "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\n";
$d1 = new \DateTime('now', new \DateTimeZone(date_default_timezone_get()));
$d2 = new \DateTime('2014-08-10 07:00:00', new \DateTimeZone(date_default_timezone_get()));

var_dump($d1 -> diff($d2));

var_dump($d1 -> sub(\DateInterval::createFromDateString("2 days")));

var_dump($d1 -> getTimestamp() - $d2 -> getTimestamp());

$s1 = 2; $s2 = 2;
for($i = 0; $i < 4; $i++){
    --$s1;
    $s2--;
    echo "\nS1 $i = ". $s1;
    echo "\nS2 $i = ". $s2;
}
echo "S1 = ". $s1;
echo "\nS2 = ". $s2;


$d = new \MongoDate(strtotime("2014-11-18 11:01:25"));
var_dump( $d);

$t = "214321343254235435435";

$t1 = "TTTTT $t";

var_dump(ltrim("=1233", "="));
var_dump(rtrim("=1233,", ","));*/


class Base {
    public $name;
    public function __construct(){
        $this -> name = "Base";
    }
}

class Child extends Base {
    public function __construct(){
        var_dump("sadfaf");
        $this -> name = "Child";
        parent::__construct();
    }
}

$cls = new Child();
//var_dump(_Class::utf8Encoding("Elizabeth De La Pe�a"));

$date = new \DateTime('now', new \DateTimeZone(date_default_timezone_get()));
$date = $date -> sub(\DateInterval::createFromDateString("11 months"));
$date -> setDate($date -> format('Y'), $date -> format('n'), 1);
echo "<pre>";
var_dump((array)$date);
for($i = 1; $i <= 12; $i++){
    $format_value = $date -> format("M Y");
    echo $i."\n";
    var_dump((array)$date);
    $date = $date -> add(\DateInterval::createFromDateString("1 month"));
}

 $a = array("c" => 12, "a" => 34);
ksort($a);

var_dump(3*4);

?>