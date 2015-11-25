<?php
/*
 * Code Owner: Cnit
 * Modified Date: 8/18/2015
 * Modified By: Phong Lam
 */
    function A(){
        B();
    }

    function B(){
        C();
    }

    function C(){
        debug_print_backtrace();
    }

    A();
?>