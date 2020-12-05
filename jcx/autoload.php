<?php
require(CORE."/Loader.php");
// var_dump(class_exists('ztf\Loader'));die;
// print_r(get_included_files());die;

// spl_autoload_register(['\\yjc\\core\\Loader', 'autoload'], true);
spl_autoload_register('\\ztf\\Loader::autoload', true);