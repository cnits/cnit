<?php

$memcache = new Memcache;
$memcache->connect('localhost',11211);
$a = $memcache->get('SMPROC4securityManager');
$b = $memcache->get('SMPROC4timeoutCacheKey');
echo '<pre>';
print_r($a);
print_r($b);
echo '<pre/>';
