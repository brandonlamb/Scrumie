<?php
/**
 * @copyright Roman Nowicki <peengle@gmail.com>
 * @licence GNU General Public Licence
 * http://scrumie.cjb.net
 */
define('PHP_RABBIT_PATH', '../../phprabbit/');
require_once (PHP_RABBIT_PATH . 'core/Application.php');
require_once('./controllers/ScrumieController.php');

session_start();

$App = Application::getInstance('config.ini.php');
$App->dispatch($App->getControllerName(), $App->getActionName());
