<?php
/**
 * @copyright Roman Nowicki <peengle@gmail.com>
 * @licence GNU General Public Licence
 * http://scrumie.cjb.net
 */
require_once('bootstrap.php');
session_start();
$application = new Application('config.ini');
$application->getFrontController()->setRouting('BaseRouting')->dispatch();
