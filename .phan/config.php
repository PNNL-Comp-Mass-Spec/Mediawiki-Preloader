<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['file_list'][] = 'Preloader.php';
$cfg['file_list'][] = 'Preloader.hooks.php';

$cfg['target_php_version'] = 8.3;

return $cfg;
