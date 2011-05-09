#!/usr/bin/env php
<?php
define('ROOT_DIR', pathinfo(__FILE__, PATHINFO_DIRNAME));
require ROOT_DIR . '/Clix/Clix/Clix.php';

//error handler
set_error_handler('coreErrorHandler');
class FatalErrorException extends Exception {};
function coreErrorHandler($errno, $errstr, $errfile, $errline) {
    throw new FatalErrorException(sprintf('%s in file %s line %s', $errstr, $errfile, $errline));
}

//starting clix
try {
    $cli = new Clix(dirname(__FILE__).DIRECTORY_SEPARATOR.'task');
    $cli->run();
} catch (Exception $e) {
    Clix::message("\n---ERROR---\nFile: %s (%s)\nMessage: %s\nType: %s",$e->getFile(), $e->getLine(), $e->getMessage(), get_class($e));
    exit;
}
