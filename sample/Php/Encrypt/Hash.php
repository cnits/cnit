<?php
/*
 * Code Owner: Cnit
 * Modified Date: 8/26/2015
 * Modified By: Phong Lam
 */

class Hash {
    public $str;

    public function __construct($_str){
        $this -> str = $_str;
    }

    public function _md5(){
        return md5($this -> str);
    }

    public function _sha1(){
        return sha1($this -> str);
    }

    public function _crc32(){
        return crc32($this -> str);
    }

    public function _crypt(){
        return crypt($this -> str);
    }
}
$hash = new Hash("123");
$hash -> _md5();