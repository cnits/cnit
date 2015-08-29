<?php
/*
 * Code Owner: Cnit
 * Modified Date: 8/26/2015
 * Modified By: Phong Lam
 */

class Hash {
    public $str;

    public function __construct($_str){
        //Todo: return string value with length = 32
        $this -> str = $_str;
    }

    public function _md5(){
        return md5($this -> str);
    }

    public function _sha1(){
        //Todo: return string value with length = 40
        return sha1($this -> str);
    }

    public function _crc32(){
        //Todo: return value is integer type with 32 bits
        return crc32($this -> str);
    }

    public function _crypt(){
        //Todo: return string value with length = 13
        return crypt($this -> str, "salt");
    }

}
$hash = new Hash("_hash");
echo "<pre>";
var_dump($hash -> _md5());
var_dump($hash -> _sha1());
var_dump($hash -> _crc32());
var_dump($hash -> _crypt());