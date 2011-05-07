<?php
/**
 * @copyright Roman Nowicki <peengle@gmail.com>
 * @licence GNU General Public Licence
 * http://scrumie.cjb.net
 */
define('PHP_RABBIT_PATH', pathinfo(__FILE__, PATHINFO_DIRNAME).'/phprabbit/');
require_once (PHP_RABBIT_PATH . 'core/Application.php');
require_once (PHP_RABBIT_PATH . 'core/BaseRouting.php');
require_once('./controller/ScrumieController.php');
require_once('Asserts.php');

session_start();

Application::getInstance('config.ini.php');
FrontController::getInstance()->setRouting('BaseRouting')->dispatch();
