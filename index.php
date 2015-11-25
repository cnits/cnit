<!DOCTYPE html>
<script charset="UTF-8" type="text/javascript" language="JavaScript" src="lib/built-in/amlich-js/JavaScript/amlich-hnd.js"></script>
<?php
	//echo '<div onload="showVietCal();">t</div>';
class _Class {
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
echo "<pre>";
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

$a = array("c" => 12, "a" => 34);
ksort($a);

var_dump(3*4);

echo 30*5.7;
echo "\n";

$bit_a = 1 << 0;
$bit_b = 1 << 5;

print_r($bit_a);echo "\n";
print_r($bit_b);echo "\n";
print_r($bit_a | $bit_b << 2);echo "\n";
print_r($bit_b | $bit_a << 2);echo "\n";
?>