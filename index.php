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

//$dsn = 'sqlite:data/scrumie.sqlite';
$dsn = 'pgsql:dbname=scrumie;host=127.0.0.1;user=postgres;port=5433';

DAO::setAdapter(new DatabaseAdapter(new PDO($dsn)));
$App = Application::getInstance();
$App->dispatch($App->getControllerName(), $App->getActionName());
