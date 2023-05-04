<?php
if (isset($_POST['_method'])) $_SERVER['REQUEST_METHOD'] = $_POST['_method'];

require_once __DIR__ . '/System/index.php';
require_once __DIR__ . '/Server/index.php';
require_once __DIR__ . '/ThirdParty/index.php';
