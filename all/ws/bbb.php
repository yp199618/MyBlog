<?php
require_once __DIR__ . '/request.php';
echo '0000000';
request(function($data) {
	echo '000';
	var_dump($data);
});