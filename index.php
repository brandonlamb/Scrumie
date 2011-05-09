<?php
/**
 * @copyright Roman Nowicki <peengle@gmail.com>
 * @licence GNU General Public Licence
 * http://scrumie.cjb.net
 */
require_once('bootstrap.php');
require_once (PHP_RABBIT_PATH . 'core/BaseRouting.php');

session_start();

Application::getInstance('config.ini');
FrontController::getInstance()->setRouting('BaseRouting')->dispatch();
