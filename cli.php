#!/usr/bin/env php
<?php
require_once('bootstrap.php');
require_once PHP_RABBIT_PATH . 'Clix/Clix/Clix.php';

try {
    $cli = new Clix(dirname(__FILE__).DIRECTORY_SEPARATOR.'task');
    $cli->run();
} catch (Exception $e) {
    Clix::message("\n---ERROR---\nFile: %s (%s)\nMessage: %s\nType: %s",$e->getFile(), $e->getLine(), $e->getMessage(), get_class($e));
    exit;
}
