<?php
/**
 * @copyright Roman Nowicki <peengle@gmail.com>
 * @licence GNU General Public Licence
 * http://scrumie.cjb.net
 */
require_once ('./core/Application.php');
require_once('./core/DAO.php');

require_once('./controllers/ScrumieController.php');

session_start();

DAO::setAdapter(new DatabaseAdapter(new PDO('sqlite:data/scrumie.sqlite')));
$App = Application::getInstance();
$App->dispatch($App->getControllerName(), $App->getActionName());
