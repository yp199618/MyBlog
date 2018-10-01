<?php
require_once __DIR__ . '/subscribe.php';
subscribe(function($data) {
	echo '000';
	var_dump($data);
});