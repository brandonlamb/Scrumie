<?php

require_once ('core/Application.php');
$App = Application::getInstance();

//the GET parameters in uri should look like that -> ?controller=Index&action=index 
$App->dispatch($App->getControllerName(), $App->getActionName());
