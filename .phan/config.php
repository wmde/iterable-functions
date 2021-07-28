<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['directory_list'] = [
	'src',
	'tests',
	'.phan/stubs',
	'vendor/wmde/traversable-iterator',
];
$cfg['suppress_issue_types'] = [];

return $cfg;
